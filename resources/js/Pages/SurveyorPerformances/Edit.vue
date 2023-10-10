<template>
  <div>
    <Head :title="`${form.surveyor_id}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/performances">Performances</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>

    <trashed-message v-if="performance.deleted_at" class="mb-6" @restore="restore"> This performances has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <select-input v-model="form.month" :error="form.errors.month" class="pb-8 pr-6 w-full lg:w-1/2" label="Month">
            <option :value="null" />
            <option v-for="(monthName, monthNumber) in months" :key="monthNumber" :value="monthNumber">{{ monthName }}</option>
          </select-input>
          <text-input v-model="form.year" :error="form.errors.year" class="pb-8 pr-6 w-full lg:w-1/2" label="Year" type="number" />
          <text-input v-model="form.quality" :error="form.errors.quality" class="pb-8 pr-6 w-full lg:w-1/2" label="Quality" type="number" />
          <text-input v-model="form.productivity" :error="form.errors.productivity" class="pb-8 pr-6 w-full lg:w-1/2" label="Productivity" type="number" />
          <text-input v-model="form.efficiency" :error="form.errors.efficiency" class="pb-8 pr-6 w-full lg:w-1/2" label="Efficiency" type="number" />
          <select-input v-model="form.surveyor_id" :error="form.errors.surveyor_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Surveyor">
            <option :value="null" />
            <option v-for="surveyor in surveyors" :key="surveyor.id" :value="surveyor.id">{{ surveyor.name }}</option>
          </select-input>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!performance.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete performance</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update performance</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
// import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  components: {
    Head,
    // Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    performance: Object,
    surveyors: Array,
    months: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        month: this.performance.month,
        year: this.performance.year,
        efficiency: this.performance.efficiency,
        productivity: this.performance.productivity,
        quality: this.performance.quality,
        surveyor_id: this.performance.surveyor_id,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/performances/${this.performance.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this performance?')) {
        this.$inertia.delete(`/performances/${this.performance.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this performance?')) {
        this.$inertia.put(`/performances/${this.performance.id}/restore`)
      }
    },
    getMonthName(monthNumber) {
      const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December',
      ]
      return months[monthNumber - 1]
    },
    getRating(score) {
      if (score >= 100) {
        return 'Excelent'
      } else if (score > 70 && score <= 99) {
        return 'Good'
      } else if (score <= 70) {
        return 'Bad'
      }
    },
  },
}
</script>
