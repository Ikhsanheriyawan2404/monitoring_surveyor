<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/branches">Branches</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="branch.deleted_at" class="mb-6" @restore="restore"> This branch has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!branch.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Branch</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Branch</loading-button>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">Surveyor</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6" colspan="2">Name</th>
        </tr>
        <tr v-for="surveyor in branch.surveyors" :key="surveyor.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/surveyors/${surveyor.id}/edit`">
              {{ surveyor.name }}
              <icon v-if="surveyor.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/surveyors/${surveyor.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="branch.surveyors.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No surveyors found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    branch: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.branch.name,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/branches/${this.branch.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this branch?')) {
        this.$inertia.delete(`/branches/${this.branch.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this branch?')) {
        this.$inertia.put(`/branches/${this.branch.id}/restore`)
      }
    },
  },
}
</script>
