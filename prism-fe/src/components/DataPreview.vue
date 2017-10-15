<template>

</template>

<script>
  import axios from 'axios'
  import inter from '../utils/interface'

  export default {
    props: ['preview_options'],
    watch: {
      preview_options: {
        handler (newValue, oldValue) {
          axios.get(inter.chartinstance + '?chart_info=' + JSON.stringify(newValue))
            .then((res) => {
              console.log(res)
            })
        },
        deep: true
      }
    }
  }
</script>

<style scoped>
  div{
    width: 100%;
    height: 100%;
  }
</style>
