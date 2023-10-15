<template>
  <div>
    <Head title="Performances" />
    <h1 class="mb-8 text-3xl font-bold">Performances</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/performances/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Performances</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Surveyor Name</th>
          <th class="pb-4 pt-6 px-6">Month</th>
          <th class="pb-4 pt-6 px-6">Year</th>
          <th class="pb-4 pt-6 px-6">Productivity</th>
          <th class="pb-4 pt-6 px-6">Quality</th>
          <th class="pb-4 pt-6 px-6">Efficiency</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Score</th>
        </tr>
        <tr v-for="performance in performances.data" :key="performance.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/performances/${performance.id}/edit`">
              {{ performance.surveyor.name }}
              <icon v-if="performance.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/performances/${performance.id}/edit`" tabindex="-1">
              <div v-if="performance.month">
                {{ getMonthName(performance.month) }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/performances/${performance.id}/edit`" tabindex="-1">
              <div v-if="performance.year">
                {{ performance.year }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/performances/${performance.id}/edit`" tabindex="-1">
              <div v-if="performance.productivity">
                {{ performance.productivity }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/performances/${performance.id}/edit`" tabindex="-1">
              <div v-if="performance.quality">
                {{ performance.quality }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/performances/${performance.id}/edit`" tabindex="-1">
              <div v-if="performance.efficiency">
                {{ performance.efficiency }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/performances/${performance.id}/edit`" tabindex="-1">
              {{ getRating(performance.score) }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/performances/${performance.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="performances.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No performances found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="performances.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    performances: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/performances', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
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
      } else if (score <= 69 && score >= 50) {
        return 'Bad'
      } else if (score < 50) {
        return 'Very Bad'
      }
    },
  },
}
</script>
