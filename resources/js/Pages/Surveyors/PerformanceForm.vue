<template>
  <form @submit.prevent="$emit('submit')">
    <div class="-mb-8 -mr-6 flex flex-wrap p-8">
      <text-input
        v-model="form.surveyor_id"
        type="hidden"
      />
      <select-input
        v-model="form.month"
        :error="form.errors.month"
        class="pb-8 pr-6 w-full lg:w-1/2"
        label="Month">
        <option :value="null" />
        <option v-for="(monthName, monthNumber) in months" :key="monthNumber" :value="monthNumber">{{ monthName }}</option>
      </select-input>
      <text-input
        v-model="form.month"
        :error="form.errors.month"
        class="w-full pb-8 pr-6 lg:w-1/2"
        label="Month"
        type="number"
      />
      <text-input
        v-model="form.year"
        :error="form.errors.year"
        class="w-full pb-8 pr-6 lg:w-1/2"
        label="Year"
        type="number"
      />
      <text-input
        v-model="form.quality"
        :error="form.errors.quality"
        class="w-full pb-8 pr-6 lg:w-1/2"
        label="Quality"
        type="number"
      />
      <text-input
        v-model="form.productivity"
        :error="form.errors.productivity"
        class="w-full pb-8 pr-6 lg:w-1/2"
        label="Productivity"
        type="number"
      />
      <text-input
        v-model="form.efficiency"
        :error="form.errors.efficiency"
        class="w-full pb-8 pr-6 lg:w-1/2"
        label="Efficiency"
        type="number"
      />
    </div>
    <slot />
  </form>
</template>

<script>
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'

export default {
  components: {
    TextInput,
    SelectInput,
  },
  props: {
    modelValue: {
      type: Object,
      required: true,
    },
    months: {
      type: Array,
      required: true,
    },
  },
  emits: ['update:modelValue', 'submit'],
  computed: {
    form: {
      get() {
        return this.modelValue
      },
      set(val) {
        this.$emit('update:modelValue', val)
      },
    },
  },
};
</script>
