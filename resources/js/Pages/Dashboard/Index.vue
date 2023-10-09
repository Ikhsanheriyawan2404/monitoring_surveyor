<template>
  <div>

    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>
    <p class="mb-8 leading-normal">Hey there! Welcome to PMS, a demo app designed to help illustrate how <a
        class="text-indigo-500 hover:text-orange-600 underline" href="https://inertiajs.com">Inertia.js</a> works.</p>

    <!-- Card Container -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Card 1 -->
      <div class="bg-white rounded-lg shadow-md p-4">
        <!-- Card Content -->
        <h2 class="text-xl font-semibold mb-2">Jumlah Surveyor</h2>
        <p>{{ $page.props.totalSurveyor }}</p>
      </div>

      <!-- Card 2 -->
      <div class="bg-white rounded-lg shadow-md p-4">
        <!-- Card Content -->
        <h2 class="text-xl font-semibold mb-2">Jumlah Survey</h2>
        <p>{{ $page.props.totalBranch }}</p>
      </div>

      <!-- Card 3 -->
      <div class="bg-white rounded-lg shadow-md p-4">
        <!-- Card Content -->
        <h2 class="text-xl font-semibold mb-2">Bar Chart</h2>
        <div>
          <Bar :data="dataBar" :options="options" />
        </div>
      </div>

      <!-- Card 4 -->
      <div class="bg-white rounded-lg shadow-md p-4">
        <!-- Card Content -->
        <h2 class="text-xl font-semibold mb-2">Line Chart</h2>
        <div>
          <Line :data="dataLine" :options="options" />
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
} from 'chart.js'
import { Bar, Line } from 'vue-chartjs'
import { Head } from '@inertiajs/inertia-vue3'
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
)

export default {
  components: {
    Head,
    Bar,
    Line,
  },
  layout: Layout,
  data() {
    return {
      // dataLine: {
      //   return chartConfig
      // },
      dataLine: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
          {
            label: 'Data One',
            backgroundColor: '#f87979',
            data: [40, 39, 10, 40, 39, 80, 40],
          },
        ],
      },
      dataBar: {
        labels: ['January', 'February', 'March'],
        datasets: [{ data: [40, 20, 12] }],
      },
      options: {
        responsive: true,
      },
    }
  },
}
</script>
