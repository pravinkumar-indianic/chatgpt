<template>
  <DefaultField
    :field="currentField"
    :errors="errors"
    :full-width-content="fullWidthContent"
    :show-help-text="showHelpText"
  >
    <template #field>
      <div class="space-y-1 text-right">
        <textarea
          v-bind="extraAttributes"
          class="block w-full form-control form-input form-input-bordered py-3 h-auto"
          id="chatGptAns"
          :dusk="field.attribute"
          :value="value"
          @input="handleChange"
          :maxlength="field.enforceMaxlength ? field.maxlength : -1"
          :placeholder="placeholder"
        ></textarea>
      <button type="button" @click="chatGptButton" title="Generate Content" component="button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
        </svg>
      </button>
        <CharacterCounter
          v-if="field.maxlength"
          :count="value.length"
          :limit="field.maxlength"
        />
      </div>
    </template>
  </DefaultField>
    <div v-show="show"
      data-te-modal-init
      class="absolute left-0 top-0 z-[1055] h-full w-full overflow-y-auto overflow-x-hidden outline-none"
      aria-labelledby="exampleModalXlLabel"
      aria-modal="true"
      role="dialog">
    <div class="relative mx-auto z-20 max-w-2xl text-left">
        <form data-form-unique-id="5gnd3xjlxaq" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden space-y-6" id="chatGptForm" @submit="checkForm">
          <div class="space-y-6">
            <h3 class="uppercase tracking-wide font-bold text-xs border-b border-gray-100 dark:border-gray-700 py-4 px-8">Generate Content</h3>
            <div class="space-y-1 ml-3 mr-3">
              Describe more about the content that you want to generate
              <input
                class="w-full form-control form-input form-input-bordered"
                required
                v-model="chatGpt"
                placeholder="Describe more about the content that you want to generate"
              />
            </div>
            <div class="space-y-1 ml-3 mr-3 col-3">
              Maximum word you want response
              <input
                class="form-control form-input form-input-bordered w-full"
                required
                placeholder="Maximum word you want response"
                v-model="maximumWord"
              />
            </div>
            <div class="space-y-1 ml-3 mr-3">
              Select the language in which you want the response
              <select v-model="defaultLang" class="w-full form-control form-input form-input-bordered">
                <option v-for="language in languages" :value="language.key">{{language.value}}
                </option>
              </select>
            </div>
          </div>
              <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 flex"><div class="flex items-center ml-auto">
                <CancelButton
                component="button"
                type="button"
                dusk="cancel-action-button"
                class="ml-auto mr-3"
                @click="close"
              >
                Close
          </CancelButton>
          <LoadingButton
            type="submit"
            ref="runButton"
            dusk="confirm-action-button"
            :loading="working"
            @click="search">
            Generate
          </LoadingButton></div></div>
        </form>
      </div>
  </div>
</template>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ['resourceName', 'resourceId', 'field'],
  computed: {
    answer() {
      return this.answer;
    },
    defaultLang(){
      return this.defaultLang;
    },
    maximumWord(){
      return this.maximumWord;
    }
  },
  data() {
    return {
      show: false,
      answer: '',
      languages: [],
      defaultLang: '',
      maximumWord: 0,
      i: 0,
      speed: 30,
      working: false,
    }
  },
  mounted: function(){
    Nova.request()
      .get('/nova-vendor/textareafield/language')
      .then((response)=>{
        this.languages = response.data.languages;
        this.defaultLang = response.data.data;
        this.maximumWord = response.data.maximum_word;
      });
  },
  methods: {

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, this.value || '')
    },
    checkForm: function(event) {
      event.preventDefault();
      const vm = this;
      let question = this.chatGpt;
      this.working = true;
      Nova.request()
        .post('/nova-vendor/textareafield/chat',{"chat":question,"language":this.defaultLang,"maximum_word":this.maximumWord})
        .then((response)=>{
          this.answer = this.answer + response.data.answer;
          this.typeWriter();
          // document.getElementById('chatGptAns').value = this.answer;
          this.show = false;
          this.chatGpt = '';
          this.working = false;
        })
        .catch((error)=>{
          Nova.error(error);
        });

    },
    chatGptButton: function(){
      this.show = true;
    },
    typeWriter: function() {
      let txt = this.answer;
      if (this.i < txt.length) {
        document.getElementById("chatGptAns").value += txt.charAt(this.i);
        this.i++;
        setTimeout(this.typeWriter, this.speed);
      }
    },
    close: function(){
      this.chatGpt = '';
      this.show = false;
      this.working = false;
    }
  },
}
</script>
