<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),
    'languages' => [
        ['key'=>'english','value'=>'English'],
        ['key'=>'hindi','value'=>'Hindi'],
        ['key'=>'spanish','value'=>'Spanish'],
        ['key'=>'arabic','value'=>'Arabic'],
        ['key'=>'french','value'=>'French'],
        ['key'=>'italian','value'=>'Italian'],
    ],
    'default_language' => 'english',
    'ai_document_model' => [
        'davinci-instruct-beta'=>'Al Documents model',
        'text-davinci-002'=>'+ Turbo - gp urbo (Best Al model)',
        'text-davinci-003'=>'Davinci - text-davinci-003 (Great Al model)',
        'curie'=>'Curie - text-curie-001 (Good Al model)',
        'babbage'=>'Babbage - text-babbage-001 (Moderate Al model)',
        'ada'=>'Ada - text-ada-001 (Fast & Simple Al model)'
    ],
    'ai_default_document_model' => 'text-davinci-003',
    'maximum_word' => 1000,

];
