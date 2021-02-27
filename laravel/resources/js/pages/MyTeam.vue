<template>
  <div id="my-team" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <h1 class="text-5xl font-bold mb-20">My Team</h1>
    <div class="grid grid-cols-2 gap-20">
      <my-team-form></my-team-form>
      <my-team-table></my-team-table>
    </div>
  </div>
</template>

<script>
  import MyTeamForm from '../components/MyTeam/Form';
  import MyTeamTable from '../components/MyTeam/TeamsTable';

  export default {
    components: {
      'my-team-form': MyTeamForm,
      'my-team-table': MyTeamTable
    },
    props: [],
    data() { return {} },
    mounted() {},
    watch: {},
    methods: {}
  }
</script>
