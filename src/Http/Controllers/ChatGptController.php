<?php

namespace Indianic\Textareafield\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatGptController extends Controller
{
    /**
     * [chat description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function chat(Request $request)
    {
        $model = $this->getSetting('ai_document_model', 'ai_default_document_model');
        $response = OpenAI::completions()->create([
            'model' => $model,
            'prompt' => $request->chat . " please translate in ".$request->language,
            'max_tokens' => (int)$request->maximum_word,
            'stop' => ['\n']
        ]);
        $answer = $response['choices'][0]['text'];
        $answer = str_replace("\n\n", '', $answer);
        return response()->json([
            'answer' => $answer
        ]);
    }
    /**
     * [language description]
     * @return [type] [description]
     */
    public function language()
    {
        $data = \DB::table('settings')->where('key', 'language')->value('value');
        if (!$data) {
            $data = config('openai.default_language');
        }
        $maximumWord = (int)$this->getSetting('maximum_word');
        $languages = config('openai.languages');
        return response()->json([
            'data' => $data,
            'languages' => $languages,
            'maximum_word' => $maximumWord
        ]);
    }
    /**
     * [getSetting description]
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    private function getSetting($key, $val='')
    {
        if (!$val) {
            $val = $key;
        }
        $response = \DB::table('settings')->where('key', $key)->value('value');
        if (!$response) {
            $response = config("openai.{$val}");
        }
        return $response;
    }
}
