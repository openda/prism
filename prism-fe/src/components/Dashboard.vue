<template>
  <div class="hello">
aaa
  </div>
</template>

<script>
  import axios from 'axios'
  import inter from '../utils/interface'

  export default {
    data: () => {
      return {
        list: []
      }
    },
    mounted: function () {
      axios.get(inter.report).then(
        (res) => {
          this.list = res.data.data
        }
      )
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
