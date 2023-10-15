<template>
  <performance-form :months="months" v-model="form" class="max-w-3xl bg-white" @submit="submit">
    <div
      class="flex items-center justify-end border-t border-gray-200 bg-gray-100 px-8 py-4"
    >
      <loading-button
        :loading="form.processing"
        class="btn-indigo"
        type="submit"
      >
        Create Performance
      </loading-button>
    </div>
  </performance-form>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton.vue'
import PerformanceForm from './PerformanceForm.vue'

export default {
  components: {
    LoadingButton,
    PerformanceForm,
  },
  remember: 'form',
  props: {
    surveyorId: Number,
    months: Array,
  },
  emits: ['success'],
  data() {
    return {
      form: this.$inertia.form({
        month: this.months,
        efficiency: '',
        productivity: '',
        quality: '',
        surveyor_id: this.surveyorId,
      }),
    }
  },
  methods: {
    submit() {
      this.form.post('/performances/surveyors', {
        onSuccess: () => {
          this.$emit('success')
          this.form.reset()
        },
      })
    },
  },
};
</script>
