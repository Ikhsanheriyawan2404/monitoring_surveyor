<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/surveyors">Surveyors</Link>
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
          <text-input v-model="form.join_date" type="date" :error="form.errors.join_date" class="pb-8 pr-6 w-full lg:w-1/2" label="Join Date" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!surveyor.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete surveyor</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Surveyor</loading-button>
        </div>
      </form>
    </div>

    <h2 class="mt-12 text-2xl font-bold">Performance</h2>
    <button class="btn-indigo" @click="modalNew = true">
        Create <span class="hidden md:inline">Performance</span>
      </button>
      <modal
        :open="modalNew"
        title="Create Performance"
        @close="modalNew = false"
      >
        <new-performance :surveyor-id="surveyor.id" :months="months" @success="modalNew = false" />
      </modal>
    <!-- Card Container -->
    <div class="mt-6 grid grid-cols-2 md:grid-cols-2 gap-4 mb-3">
      <div class="bg-white rounded-lg shadow-md p-4">
        <!-- Card Content -->
        <h2 class="text-xl font-semibold mb-2">Line Chart</h2>
        <div>
          <Line :data="dataLine" :options="options" />
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-4">
        <!-- Card Content -->
        <h2 class="text-xl font-semibold mb-2">Line Chart</h2>
        <div>
          <Line :data="dataLine2" :options="options" />
        </div>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">Data Performance : {{ surveyor.name }}</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Month</th>
          <th class="pb-4 pt-6 px-6">Productivity</th>
          <th class="pb-4 pt-6 px-6">Quality</th>
          <th class="pb-4 pt-6 px-6">Efficiency</th>
          <th class="pb-4 pt-6 px-6">Score</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Rating</th>
        </tr>
        <tr v-for="performance in surveyor.performances" :key="performance.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/performances/${performance.id}/edit`">
              {{ getMonthName(performance.month) }}
              <icon v-if="performance.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/performances/${performance.id}/edit`">
              {{ performance.productivity }} %
              <icon v-if="performance.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/performances/${performance.id}/edit`">
              {{ performance.quality }} %
              <icon v-if="performance.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/performances/${performance.id}/edit`">
              {{ performance.efficiency }} %
              <icon v-if="performance.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/performances/${performance.id}/edit`">
              {{ performance.score }} %
              <icon v-if="performance.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/performances/${performance.id}/edit`">
              {{ getRating(performance.score) }}
              <icon v-if="performance.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/performances/${performance.id}/edit`">
              {{ performance.name }}
              <icon v-if="performance.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/performances/${performance.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="surveyor.performances.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No performance found.</td>
        </tr>
      </table>
    </div>

    <h2 class="mt-12 text-2xl font-bold">Tasks</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6" colspan="2">Name</th>
        </tr>
        <tr v-for="task in surveyor.tasks" :key="task.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/tasks/${task.id}/edit`">
              {{ task.name }}
              <icon v-if="task.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/tasks/${task.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="surveyor.tasks.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No task found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
} from 'chart.js'
import { Line } from 'vue-chartjs'
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'
import Modal from '@/Shared/Modal.vue';
import NewPerformance from './_NewPerformance.vue';

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  PointElement,
  LineElement,
)

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    Line,
    Modal,
    NewPerformance,
  },
  layout: Layout,
  props: {
    surveyor: Object,
    branches: Array,
    performanceComponent: Array,
    performanceFinal: Array,
    months: Array,
  },
  remember: 'form',
  data() {
    return {
      dataLine: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [
          {
            label: 'Efficiency',
            backgroundColor: '#00D8FF',
            data: this.performanceComponent['efficiency'],
          },
          {
            label: 'Productivity',
            backgroundColor: '#DD1B16',
            data: this.performanceComponent['productivity'],
          },
          {
            label: 'Quality',
            backgroundColor: '#41B883',
            data: this.performanceComponent['quality'],
          },
        ],
      },
      dataLine2: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [
          {
            label: 'Data Performance Score : ' + this.surveyor.name,
            backgroundColor: '#f87979',
            data: this.performanceFinal,
          },
        ],
      },
      options: {
        responsive: true,
      },
      form: this.$inertia.form({
        name: this.surveyor.name,
        join_date: this.surveyor.join_date,
        branch_id: this.surveyor.branch_id,
      }),
      modalNew: false,
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
