<template>
  <div>
    <ul class="divide-y divide-gray-200">
      <team v-for="team in teams" :key="team.id" :team="team"></team>
    </ul>
  </div>
</template>

<script>
import TeamsTableItem from './TeamsTableItem';

export default {
  components: {
    Team: TeamsTableItem
  },
  data() {
    return {
      teams: []
    }
  },
  mounted() {
    axios.get('/api/v1/teams')
    .then(data => {
      this.teams = data.data.teams;
    })
  }
}
</script>