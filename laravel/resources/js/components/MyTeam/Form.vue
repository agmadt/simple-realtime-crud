<template>
  <div>
    <h2 class="text-xl mb-5">Add new team member</h2>
    <form class="space-y-5">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <div class="mt-1">
          <input type="text" name="name" id="name" placeholder="Calvin Hawkins"
            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 p-3 border rounded-md"
            v-model="name.value"
            >
            <p class="mt-2 text-sm text-red-500" v-if="name.error">{{ name.error }}</p>
        </div>
      </div>
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <div class="mt-1">
          <input type="text" name="email" id="email" placeholder="you@example.com"
            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 p-3 border rounded-md"
            v-model="email.value">
          <p class="mt-2 text-sm text-red-500" v-if="email.error">{{ email.error }}</p>
        </div>
      </div>
      <div>
        <label for="photo" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
          Photo: {{ photo.name }}
        </label>
        <div class="mt-1">
          <div id="photo-drop-zone" class="w-full flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
            <div class="space-y-1 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <div class="flex text-sm text-gray-600">
                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                  <span>Upload a file</span>
                  <input id="file-upload" name="file-upload" type="file" class="sr-only" accept="image/jpeg" @change="onFileChange($event)">
                </label>
                <p class="pl-1">or drag and drop</p>
              </div>
              <p class="text-xs text-gray-500">
                JPG up to 100MB
              </p>
            </div>
          </div>
        </div>
        <p class="mt-2 text-sm text-red-500" v-if="photo.error">{{ photo.error }}</p>
      </div>
      <button type="button"
        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        :class="[
          submitButton.disabled ? 'opacity-50 cursor-not-allowed' : ''
        ]"
        :disabled="submitButton.disabled"
        @click="submitForm()"
      >
        <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Submit
      </button>
      <div class="flex items-center text-white text-sm font-bold px-4 py-3 rounded"
        :class="[
          form.success ? 'bg-blue-500' : 'bg-red-500'
        ]"
        v-if="form.success || form.error"
      >
        {{ form.message }}
      </div>
    </form>
  </div>
</template>

<script>
  const axios = require('axios').default;
  
  export default {
    data() {
      return {
        name: {
          value: '',
          error: null
        },
        email: {
          value: '',
          error: null
        },
        photo: {
          name: '',
          value: '',
          error: null
        },
        submitButton: {
          disabled: true,
        },
        form: {
          processing: false,
          error: false,
          success: false,
          message: null
        }
      }
    },
    watch: {
      name: {
        handler(newName, oldName) {
          this.submitButton.disabled = true;
          
          if (this.name.value.length > 0 && this.email.value.length > 0) {
            this.submitButton.disabled = false;
          }
        },
        deep: true
      },
      email: {
        handler(newEmail, oldEmail) {
          this.submitButton.disabled = true;

          if (newEmail.value.length > 0 && this.name.value.length > 0) {
            this.submitButton.disabled = false;
          }
        },
        deep: true
      }
    },
    methods: {
      submitForm() {

        this.form = {
          processing: true,
          message: null,
          success: false,
          error: false
        }

        this.submitButton.disabled = true;
        
        if (this.name.value.length == 0) {
          this.name.error = "The name field is required";
        } else {
          this.name.error = null;
        }

        if (this.email.value.length == 0) {
          this.email.error = "The email field is required";
        } else {
          this.email.error = null;
        }

        if (this.name.value.length == 0 || this.email.value.length == 0) {
          return;
        }

        let formData = new FormData();
        formData.append('name', this.name.value);
        formData.append('email', this.email.value);
        
        if (this.photo.value) {
          formData.append('photo', this.photo.value);
        }

        axios.post('/api/v1/teams', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(data => {
          this.form = {
            success: true,
            message: data.data.message
          }

          this.name.value = '';
          this.email.value = '';
          this.photo = {
            name: '',
            value: '',
          };

          let temporaryTeams = this.$store.state.teams;

          temporaryTeams.unshift({
            id: data.data.data.id,
            name: data.data.data.Name,
            email: data.data.data.Email,
            photo: data.data.data.Photo[0].url
          })

          this.$store.commit('setTeams', temporaryTeams)
        })
        .catch(err => {
          if (err.response) {
            if (err.response.status == 422) {
              if (err.response.data.errors.name) {
                this.name.error = err.response.data.errors.name[0];
              }

              if (err.response.data.errors.email) {
                this.email.error = err.response.data.errors.email[0];
              }

              return;
            }

            this.form = {
              error: true,
              message: err.response.data.message
            }

            return;
          }

          console.log(err)
        })
        .finally(data => {
          this.submitButton.disabled = true;
          this.form.processing = false;
        })
      },
      onFileChange(e) {
        var files = e.target.files || e.dataTransfer.files;
        if (!files.length) return;

        if (files[0].size > 102400000) {
          this.photo.error = "File size must not exceed 100MB"
          return;
        }

        if (files[0].type != "image/jpeg") {
          this.photo.error = "Photo must be a jpg"
          return;
        }

        this.photo = {
          name: files[0].name,
          value: files[0],
          error: null
        }
      },
    }
  }
</script>