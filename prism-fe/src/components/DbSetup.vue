<template>
  <div class="container">
    <Row :gutter="16">
      <Col span="10">
        <h1>数据库配置</h1>
        <Form :label-width="80">
          <Form-item label="数据库类型">
            <Select v-model="selected" v-on:on-change="handleChange" placeholder="请选择数据库类型">
              <Option v-for="type in dbType" :value="type.value" :key="type.key">{{ type.desc }}</Option>
            </Select>
          </Form-item>
          <Form-item v-for="item in formItems" :label="item.desc">
            <Input type="text" :name="item.key" :placeholder="item.desc" v-model="formData[item.key]"></Input>
          </Form-item>
          <Form-item>
            <Button type="info" @click="handleSave">保存配置</Button>
            <Button type="warning" @click="handleTest">测试连接</Button>
            <span class="err">{{ errorMsg }}</span>
          </Form-item>
        </Form>
      </Col>
      <Col span="14">
          <h1>已存数据库配置</h1>
          <Table :columns="columns" :data="databases"></Table>
      </Col>
    </Row>
  </div>
</template>
<script>
  import axios from 'axios'
  import qs from 'qs'
  import inter from '../utils/interface'
  import { dbType } from '../utils/config'

  export default {
    data: () => {
      return {
        dbType: dbType,
        selected: '',
        formItems: [],
        cache: {},
        formData: {},
        errorMsg: '',
        columns: [
          {
            title: '序号',
            key: 'index'
          },
          {
            title: '编号',
            key: 'no'
          },
          {
            title: '类型',
            key: 'type'
          },
          {
            title: '备注',
            key: 'brief'
          }
        ],
        databases: []
      }
    },
    methods: {
      handleChange: function (selected) {
        this.formItems = []
        if (this.cache[selected]) {
          this.formItems = this.cache[selected]
        } else {
          axios.get(inter.dblinktemplate, {
            params: {
              db_type: selected
            }
          })
            .then((res) => {
              let data = res.data.data
              if (data.template) {
                this.formItems = data.template
                this.cache[selected] = data.template
                this.formData = {}
              }
            })
        }
      },
      handleTest: function () {
        let dbType = this.selected
        let params = {}
        for (let key in this.formData) {
          params[key] = this.formData[key]
        }
        axios.post(inter.dblink, qs.stringify({
          db_type: dbType,
          link_info: JSON.stringify(params)
        }))
          .then((res) => {
            this.errorMsg = res.data.msg
            setTimeout(() => {
              this.errorMsg = ''
            }, 2000)
          })
      },
      handleSave: function () {
        let dbType = this.selected
        let params = {}
        for (let key in this.formData) {
          params[key] = this.formData[key]
        }
        axios.put(inter.dblink + '?db_type=' + dbType + '&link_info=' + JSON.stringify(params))
          .then((res) => {
            this.errorMsg = res.data.msg
            this.renderDatabase()
          })
      },
      renderDatabase: function () {
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
    },
    mounted: function () {
      this.renderDatabase()
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
