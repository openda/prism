<template>
  <div id="chartForm">
    <div :hidden="isShow" >
      <h1>图表配置</h1>
      <Form :label-width="120">
        <Col span="12">
        <Form-item label="图表标题">
          <Input type="text" v-model="chart_instance.title" placeholder="请输入图表标题"></Input>
        </Form-item>
        <Form-item label="横轴名称">
          <Input type="text" v-model="chart_instance.xAxis.name" placeholder="请输入横轴名称"></Input>
        </Form-item>
        <Form-item label="横轴类型">
          <Select v-model="chart_instance.xAxis.type" placeholder="请选择横轴类型">
            <Option value="value">数值轴</Option>
            <Option value="category">类目轴</Option>
            <Option value="time">时间轴</Option>
          </Select>
        </Form-item>
        <Form-item label="横轴对应数据列">
          <Select v-model="chart_instance.xAxis.data" placeholder="请选择横轴对应数据列">
            <Option v-for="(col, index) in tableCols" :value="col" :key="index">{{ col }}</Option>
          </Select>
        </Form-item>
        </Col>
        <Col span="12">
        <Form-item label="图表类型">
          <Select v-model="chart_instance.type" placeholder="请选择图表类型">
            <Option value="line">折线图</Option>
            <Option value="bar">柱状/条形图</Option>
          </Select>
        </Form-item>
        <Form-item label="纵轴名称">
          <Input type="text" v-model="chart_instance.yAxis.name" placeholder="请输入纵轴名称"></Input>
        </Form-item>
        <Form-item label="纵轴类型">
          <Select v-model="chart_instance.yAxis.type" placeholder="请选择纵轴类型">
            <Option value="value">数值轴</Option>
            <Option value="category">类目轴</Option>
            <Option value="time">时间轴</Option>
          </Select>
        </Form-item>
        <Form-item label="纵轴对应数据列">
          <Select v-model="chart_instance.yAxis.data" placeholder="请选择纵轴对应数据列">
            <Option v-for="(col, index) in tableCols" :value="col" :key="index">{{ col }}</Option>
          </Select>
        </Form-item>
        <Form-item>
          <Button type="info" @click="handlePreview">预览</Button>
        </Form-item>
        </Col>
      </Form>
    </div>
    <Col span="24">
      <div id="chartArea">
        <chart :chart_options="chart_options"></chart>
      </div>
    </Col>
  </div>
</template>

<script>
  import Chart from './Chart.vue'
  export default {
    data: () => {
      return {
        chart_instance: {
          title: '',
          xAxis: {
            name: '',
            type: '',
            data: ''
          },
          yAxis: {
            name: '',
            type: '',
            data: ''
          },
          type: ''
        },
        chart_options: null
      }
    },
    props: ['isShow', 'tableCols'],
    components: {
      'chart': Chart
    },
    methods: {
      handlePreview: function () {
        this.chart_options = this.chart_instance
      }
    }
  }
</script>

<style scoped>
  #chartForm{
    display: flex;
    flex-direction: column;
  }
h1{
  text-align: center;
  margin-bottom: 0.5em;
}
  #chartArea{
    height: 300px;
  }
</style>
