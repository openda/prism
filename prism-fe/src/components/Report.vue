<template>
  <div class="container">
    <Row :gutter="16">
      <Col span="8">
        <Row :gutter="16">
        <h1>选择数据源</h1>
          <Form :label-width="100">
            <Form-item label="数据库配置">
              <Select v-on:on-change="handleDB" placeholder="请选择数据库配置" :disabled="disable">
                <Option v-for="item in databases" :value="item.no" >{{ item.no }}（{{ item.brief }}）</Option>
              </Select>
            </Form-item>
            <Form-item v-if="dbReady" label="数据库">
              <Select  v-model="db_name" v-on:on-change="handleDBName" placeholder="请选择数据库" :disabled="disable">
                <Option v-for="db in dbs" :value="db" >{{ db }}</Option>
              </Select>
            </Form-item>
            <Form-item v-if="tableReady" label="数据表">
              <Select  v-model="table_name" v-on:on-change="handleTableName" placeholder="请选择数据表" :disabled="disable">
                <Option v-for="table in tableList" :value="table" >{{ table }}</Option>
              </Select>
            </Form-item>
            <Form-item v-if="colReady" label="数据列">
              <Checkbox-group v-model="checkGroup" @on-change="checkChange" >
                <Checkbox v-for="col in colList" :label="col.field" :disabled="disable"></Checkbox>
              </Checkbox-group>
            </Form-item>
            <Form-item v-if="confirm">
              <Button type="info" @click="handleLock" :disabled="disable">确定</Button>
              <Button type="warning" @click="handleLock" :disabled="!disable">取消</Button>
            </Form-item>
          </Form>
        </Row>
        <Row :gutter="16" v-if="disable">
          <h1>图表配置</h1>
          <Form :label-width="100">
            <Form-item label="图表类型">
              <Select v-on:on-change="setChartType" placeholder="请选择图表类型">
                <Option v-for="item in chartTypeList" :value="item.no" >{{ item.no }}</Option>
              </Select>
            </Form-item>
          </Form>
        </Row>
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
        tableReady: false,
        colReady: false,
        dbs: [],
        tableList: [],
        indeterminate: true,
        checkGroup: [],
        colList: [],
        disable: false,
        confirm: false,
        chartTypeList: [],
        chart_type: null
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
        }).then((res) => {
          let data = res.data.data
          switch (type) {
            case 'db_name':
              if (!data) {
                console.log(data)
              } else {
                for (let index = 0; index < data.length; index++) {
                  this.dbs.push(data[index].db_name)
                }
                this.dbReady = true
              }
              break
            case 'db_table':
              if (!data) {
                console.log(data)
              } else {
                for (let index = 0; index < data.length; index++) {
                  let tables = data[index].tables
                  for (let count = 0; count < tables.length; count++) {
                    this.tableList.push(tables[count].table_name)
                  }
                }
                this.tableReady = true
              }
              break
            case 'db_struct':
              if (!data) {
                console.log(data)
              } else {
                for (let index = 0; index < data.length; index++) {
                  let table = data[index].tables
                  let tableStructures = table.table_structure
                  for (let i = 0; i < tableStructures.length; i++) {
                    this.colList.push(tableStructures[i])
                  }
                }
                this.colReady = true
              }
              break
          }
        })
      },
      getChartInfo: function (type) {
        axios.get(inter.charttemplate, {
          params: {
            chart_type: this.chart_type
          }
        })
          .then((res) => {
            console.log(res)
          })
      },
      handleDB: function (selected) {
        this.db_id = selected
        this.dbReady = false
        this.tableReady = false
        this.colReady = false
        this.dbs = []
        this.getCol('db_name')
      },
      handleDBName: function (selected) {
        this.db_name = selected
        this.getCol('db_table')
      },
      handleTableName: function (selected) {
        this.table_name = selected
        this.getCol('db_struct')
      },
      checkChange: function (data) {
        if (this.checkGroup.length > 0) {
          this.confirm = true
        } else {
          this.confirm = false
        }
      },
      handleLock: function () {
        this.lock = !this.lock
        this.disable = this.lock
      }
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
              type: data[index].db_type,
              brief: data[index].brief
            })
          }
          this.databases = array
        })
      this.getChartInfo('chartList')
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
