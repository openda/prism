<template>
  <div class="container">
    <Row :gutter="16">
      <Col span="8">
      <Form :label-width="80">
        <Form-item label="数据库配置">
          <Select v-on:on-change="handleDB" placeholder="请选择数据库配置">
            <Option v-for="item in databases" :value="item.no" >{{ item.no }}（{{ item.type }}）</Option>
          </Select>
          <Select v-if="dbReady" v-model="db_name" v-on:on-change="handleDBName" placeholder="请选择数据库">
            <Option v-for="db in dbs" :value="db" >{{ db }}</Option>
          </Select>
        </Form-item>
      </Form>
      </Col>
      <Col span="16">
      </Col>
    </Row>
  </div>
</template>
<script>
  import axios from 'axios'
//  import qs from 'qs'
  import inter from '../utils/interface'

  export default {
    data: () => {
      return {
        databases: [],
        db_id: null,
        db_name: null,
        table_name: null,
        dbReady: false,
        dbs: []
      }
    },
    methods: {
      getCol: function (type) {
        axios.get(inter.userdb, {
          params: {
            db_link_id: this.db_id,
            db_name: this.db_name,
            table_name: this.table_name
          }
        }).then(function (res) {
          let data = res.data.data
          switch (type) {
            case 'db_name':
              for (let index = 0; index < data.length; index++) {
                this.dbs.push(data[index].db_name)
              }
              this.dbReady = true
          }
        })
      },
      handleDB: function (selected) {
        this.db_id = selected
        this.dbReady = false
        this.dbs = []
        axios.get(inter.userdb, {
          params: {
            db_link_id: this.db_id,
            db_name: this.db_name,
            table_name: this.table_name
          }
        }).then(function (res) {
          let data = res.data.data
          for (let index = 0; index < data.length; index++) {
            this.dbs.push(data[index].db_name)
          }
          this.dbReady = true
        })
      }
    },
    handleDBName: function () {
      console.log()
    },
    mounted: function () {
      axios.get(inter.dblink)
        .then((res) => {
          let data = res.data.data
          var array = []
          for (var index = 0; index < data.length; index++) {
            array.push({
              index: index + 1,
              no: data[index].db_id,
              type: data[index].db_type
            })
          }
          this.databases = array
        })
    }
  }
</script>
<style scoped>
  .container {
    padding: 1em 0.5em;
  }
  h1 {
    text-align: center;
    margin-bottom: 0.5em;
  }
  span.err{
    color: red;
  }
</style>
