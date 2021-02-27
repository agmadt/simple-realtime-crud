<template>
  <li class="py-4 flex">
    <img class="h-10 w-10 rounded-full" :src="team.photo" alt=""
      v-if="team.photo"
    >
    <div class="ml-3">
      <p class="text-sm font-medium text-gray-900">{{ team.name }}</p>
      <p class="text-sm text-gray-500">{{ team.email }}</p>
    </div>
  </li>
</template>

<script>
export default {
  props: ["team"]
}
</script>