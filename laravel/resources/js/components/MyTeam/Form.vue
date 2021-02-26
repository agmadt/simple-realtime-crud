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
          Photo
        </label>
        <div class="mt-1">
          <div class="w-full flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
            <div class="space-y-1 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <div class="flex text-sm text-gray-600">
                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                  <span>Upload a file</span>
                  <input id="file-upload" name="file-upload" type="file" class="sr-only">
                </label>
                <p class="pl-1">or drag and drop</p>
              </div>
              <p class="text-xs text-gray-500">
                PNG, JPG, GIF up to 10MB
              </p>
            </div>
          </div>
        </div>
      </div>
      <button type="button"
        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        :class="[
          submitButton.disabled ? 'opacity-50 cursor-not-allowed' : ''
        ]"
        :disabled="submitButton.disabled"
        @click="submitForm()"
      >
        Submit
      </button>
      <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3 rounded"
        v-if="form.submitted"
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
          value: '',
          error: null
        },
        submitButton: {
          disabled: true,
        },
        form: {
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
          
          if (newName.value.length > 0 && this.email.value.length > 0) {
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

        axios.post('/api/v1/teams', {
          name: this.name.value,
          email: this.email.value,
        })
        .then(data => {

        })
        .catch(err => {
          if (err.response.status == 422) {
            if (err.response.data.errors.name) {
              this.name.error = err.response.data.errors.name[0];
            }

            if (err.response.data.errors.email) {
              this.email.error = err.response.data.errors.email[0];
            }
          }
        })
      }
    }
  }
</script>