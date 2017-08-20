<template>
  <div>
    <h1>{{ msg }}</h1>
  </div>
</template>
<script>
  import axios from 'axios'
  import inter from '../utils/interface'
  import { dbType } from '../utils/config'

  export default {
    data: function () {
      return {
        msg: dbType.join(';')
      }
    },
    beforeCreate: function () {
      axios.get(inter.dblink, {
        params: {
          db_type: 'mysql'
        }
      })
        .then(function (res) {
          console.log(this)
        })
    }
  }
</script>
