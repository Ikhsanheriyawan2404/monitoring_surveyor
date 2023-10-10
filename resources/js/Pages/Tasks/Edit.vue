<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/tasks">Tasks</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>

    <trashed-message v-if="task.deleted_at" class="mb-6" @restore="restore"> This task has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
          <select-input v-model="form.surveyor_id" :error="form.errors.surveyor_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Surveyor">
            <option :value="null" />
            <option v-for="surveyor in surveyors" :key="surveyor.id" :value="surveyor.id">{{ surveyor.name }}</option>
          </select-input>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!task.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Task</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Task</loading-button>
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
    task: Object,
    surveyors: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.task.name,
        surveyor_id: this.task.surveyor_id,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/tasks/${this.task.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this task?')) {
        this.$inertia.delete(`/tasks/${this.task.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this task?')) {
        this.$inertia.put(`/tasks/${this.task.id}/restore`)
      }
    },
  },
}
</script>
