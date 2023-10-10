<template>
  <div>
    <Head title="Create Performance" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/performances">Performances</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.month" :error="form.errors.month" class="pb-8 pr-6 w-full lg:w-1/2" label="Month" type="number" />
          <text-input v-model="form.year" :error="form.errors.year" class="pb-8 pr-6 w-full lg:w-1/2" label="Year" type="number" />
          <text-input v-model="form.quality" :error="form.errors.quality" class="pb-8 pr-6 w-full lg:w-1/2" label="Quality" type="number" />
          <text-input v-model="form.productivity" :error="form.errors.productivity" class="pb-8 pr-6 w-full lg:w-1/2" label="Productivity" type="number" />
          <text-input v-model="form.efficiency" :error="form.errors.efficiency" class="pb-8 pr-6 w-full lg:w-1/2" label="Efficiency" type="number" />
          <select-input v-model="form.surveyor_id" :error="form.errors.surveyor_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Surveyor">
            <option :value="null" />
            <option v-for="surveyor in surveyors" :key="surveyor.id" :value="surveyor.id">{{ surveyor.name }}</option>
          </select-input>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Performance</loading-button>
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

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    surveyors: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        month: '',
        year: '',
        efficiency: '',
        productivity: '',
        quality: '',
        surveyor_id: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/performances')
    },
  },
}
</script>
