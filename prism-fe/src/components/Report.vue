<template>
  <div class="container">
    <Row :gutter="16">
      <Col span="8">
        <Row :gutter="16">
        <h1>选择数据源</h1>
          <Form :label-width="100">
            <Form-item label="数据库配置">
              <Select v-on:on-change="handleDB" placeholder="请选择数据库配置" :disabled="disable">
                <Option v-for="item in databases" :value="item.no" :key="item.no">{{ item.no }}（{{ item.brief }}）</Option>
              </Select>
            </Form-item>
            <Form-item v-if="dbReady" label="数据库">
              <Select  v-model="db_name" v-on:on-change="handleDBName" placeholder="请选择数据库" :disabled="disable">
                <Option v-for="(db,index) in dbs" :value="db" :key="index" >{{ db }}</Option>
              </Select>
            </Form-item>
            <Form-item v-if="tableReady" label="数据表">
              <Select  v-model="table_name" v-on:on-change="handleTableName" placeholder="请选择数据表" :disabled="disable">
                <Option v-for="(table,index) in tableList" :value="table" :key="index" >{{ table }}</Option>
              </Select>
            </Form-item>
            <Form-item v-if="colReady" label="选择指标列">
              <Checkbox-group v-model="checkTargetGroup" @on-change="checkTargetChange" >
                <Checkbox v-for="(col,index) in colList" :label="col.field" :key="index" :disabled="disable"></Checkbox>
              </Checkbox-group>
            </Form-item>
            <Form-item v-if="colReady" label="指标列操作">
              <div v-for="(item, index) in checkTargetGroup">
                <div>{{ item }}</div>

                  <RadioGroup v-model="targetOperate[item]" @on-change="handleOperateChange" >
                    <Radio v-for="(col,index) in OperateList" :label="col" :key="index" :disabled="disable" ></Radio>
                  </RadioGroup>

              </div>

            </Form-item>
            <Form-item v-if="colReady" label="选择维度列">
              <Checkbox-group v-model="checkDimensionGroup" @on-change="checkDimensionChange" >
                <Checkbox v-for="(col,index) in colList" :label="col.field" :key="index" :disabled="disable"></Checkbox>
              </Checkbox-group>
            </Form-item>
            <Form-item v-if="colReady" label="维度列操作">
              <div v-for="(item, index) in checkDimensionGroup">
                <div>{{ item }}</div>

                <RadioGroup v-model="dimensionOperate[item]" @on-change="handleOperateChange">
                  <Radio v-for="(col,index) in OperateList" :label="col" :key="index" :disabled="disable" ></Radio>
                </RadioGroup>

              </div>

            </Form-item>
            <Form-item v-if="confirm">
              <Button type="info" @click="handleLock" :disabled="disable">确定</Button>
              <Button type="warning" @click="handleLock" :disabled="!disable">取消</Button>
            </Form-item>
          </Form>
        </Row>
      <Row :gutter="16">
        <Col span="24">
          <data-preview :previewOption="previewOption"></data-preview>
        </Col>
      </Row>
      </Col>
      <Col span="16">
        <chart-form :isShow="confirm" :dataOption="previewOption" ></chart-form>
      </Col>
    </Row>
  </div>
</template>
<script>
  import axios from 'axios'
//  import qs from 'qs'
  import inter from '../utils/interface'
  import DataPreview from './DataPreview.vue'
  import ChartForm from './ChartForm.vue'

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
        chart_type: null,
        userData: [],
        checkTargetGroup: [],
        checkDimensionGroup: [],
        previewOption: null,
        OperateList: ['AVG', 'COUNT', 'MAX', 'MIN', 'SUM'],
        checkTargetGroupOperate: null,
        targetOperate: {},
        dimensionOperate: {}
      }
    },
    components: {
      'data-preview': DataPreview,
      'chart-form': ChartForm
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
                this.confirm = true
              }
              break
          }
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
      checkDimensionChange: function () {
        this.checkGroupChange()
      },
      checkTargetChange: function () {
        this.checkGroupChange()
      },
      checkGroupChange: function () {
        let checkTargetExpressions = []
        let checkDimensionExpressions = []
        for (let i = 0; i < this.checkTargetGroup.length; i++) {
          if (this.targetOperate[this.checkTargetGroup[i]]) {
            checkTargetExpressions[i] = this.targetOperate[this.checkTargetGroup[i]] + '(' + this.checkTargetGroup[i] + ')'
          } else {
            checkTargetExpressions[i] = this.checkTargetGroup[i]
          }
        }
        for (let i = 0; i < this.checkDimensionGroup.length; i++) {
          if (this.dimensionOperate[this.checkDimensionGroup[i]]) {
            checkDimensionExpressions[i] = this.dimensionOperate[this.checkDimensionGroup[i]] + '(' + this.checkDimensionGroup[i] + ')'
          } else {
            checkDimensionExpressions[i] = this.checkDimensionGroup[i]
          }
        }
        let expressions = checkTargetExpressions.concat(checkDimensionExpressions)
        let option = {
          dblink_id: this.db_id,
          from: [this.db_name + '.' + this.table_name],
          expressions: expressions
        }
        if (checkDimensionExpressions.length > 0) {
          option['group'] = checkDimensionExpressions
        }
        this.previewOption = option
      },
      handleLock: function () {
        this.lock = !this.lock
        this.disable = this.lock
        let info = [this.db_id, this.db_name, this.table_name]
        let prefix = info.join(' | ')
        this.userData = []
        for (let i = 0; i < this.checkGroup.length; i++) {
          let col = this.checkGroup[i]
          this.userData.push(prefix + ' | ' + col)
        }
      },
      handleOperateChange: function (ev) {
        console.log(this.dimensionOperate, this.targetOperate)
        this.checkGroupChange()
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
    }
  }
</script>
<style scoped>
  .container {
    padding: 1em 0.5em;
    flex-grow: 1;
  }
  h1 {
    text-align: center;
    margin-bottom: 0.5em;
  }
  span.err{
    color: red;
  }
</style>
