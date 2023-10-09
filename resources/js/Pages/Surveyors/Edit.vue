<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/surveyors">Surveyor</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="surveyor.deleted_at" class="mb-6" @restore="restore"> This surveyor has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
          <select-input v-model="form.branch_id" :error="form.errors.branch_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Branch">
            <option :value="null" />
            <option v-for="branch in branches" :key="branch.id" :value="branch.id">{{ branch.name }}</option>
          </select-input>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!surveyor.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete surveyor</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Surveyor</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    surveyor: Object,
    branches: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.surveyor.name,
        branch_id: this.surveyor.branch_id,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/surveyors/${this.surveyor.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this surveyor?')) {
        this.$inertia.delete(`/surveyors/${this.surveyor.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this surveyor?')) {
        this.$inertia.put(`/surveyors/${this.surveyor.id}/restore`)
      }
    },
  },
}
</script>
