<template>
  <div>

    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>
    <p class="mb-8 leading-normal">Hey there! Welcome to PMS. This is a APP built using Vue and Inertia.</p>

    <!-- Card Container -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
      <Link href="/surveyors">
        <div class="bg-white rounded-lg shadow-md p-4">
          <!-- Card Content -->
          <h2 class="text-xl font-semibold mb-2">Total Surveyors</h2>
          <p>{{ $page.props.totalSurveyor }}</p>
        </div>
      </Link>

      <Link href="/branches">
        <div class="bg-white rounded-lg shadow-md p-4">
          <!-- Card Content -->
          <h2 class="text-xl font-semibold mb-2">Total Branches</h2>
          <p>{{ $page.props.totalBranch }}</p>
        </div>
      </Link>

      <Link href="/tasks">
        <div class="bg-white rounded-lg shadow-md p-4">
          <!-- Card Content -->
          <h2 class="text-xl font-semibold mb-2">Total Tasks Surveyor</h2>
          <p>{{ $page.props.totalTask }}</p>
        </div>
      </Link>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="bg-white rounded-lg shadow-md p-4">
        <!-- Card Content -->
        <h2 class="text-xl font-semibold mb-2">Chart Master Data</h2>
        <div>
          <Bar :data="dataBar" :options="options" />
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-4">
        <!-- Card Content -->
        <h2 class="text-xl font-semibold mb-2">Chart Total Rating Surveyors</h2>
        <div>
          <Pie :data="dataPie" :options="options" />
        </div>
      </div>
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
  ArcElement,
} from 'chart.js'
import { Bar, Pie } from 'vue-chartjs'
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  PointElement,
  LineElement,
  ArcElement,
)

export default {
  components: {
    Head,
    Bar,
    Link,
    Pie,
  },
  layout: Layout,
  props: {
    totalBranch: Number,
    totalSurveyor: Number,
    totalTask: Number,
    totalCountAllCategoryRating: Array,
  },
  data() {
    return {
      dataPie: {
        labels: ['Excelent', 'Good', 'Bad'],
        datasets: [
          {
            backgroundColor: ['#00D8FF', '#41B883', '#DD1B16'],
            data: this.totalCountAllCategoryRating,
          },
        ],
      },
      dataBar: {
        labels: ['Surveyors', 'Tasks', 'Branches'],
        datasets: [{label: 'Total Data Master', data: [this.totalSurveyor, this.totalTask, this.totalBranch], backgroundColor: ['#00D8FF', '#41B883', '#DD1B16']}],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      },
    }
  },
}
</script>
