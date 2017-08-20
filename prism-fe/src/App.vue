<style scoped>
  .layout{
    background: #f5f7f9;
  }
  .layout-logo{
    height: 30px;
    float: left;
    position: relative;
    top: 15px;
    left: 20px;
  }
  .layout-logo img{
      height: 30px;
    vertical-align: top;
  }
  .layout-nav{
    width: 420px;
    margin: 0 auto;
  }
  .layout-nav a{
    color:inherit;
  }
  .layout-assistant{
    width: 300px;
    margin: 0 auto;
    height: inherit;
  }
  .layout-breadcrumb{
    padding: 10px 15px 0;
  }
  .layout-content{
    min-height: 83vh;
    margin: 15px;
    overflow: hidden;
    background: #fff;
    border-radius: 4px;
  }
  .layout-content-main{
    padding: 10px;
  }
  .layout-copy{
    text-align: center;
    padding: 10px 0 20px;
    color: #9ea7b4;
  }
</style>
<template>
  <div class="layout">
    <Menu mode="horizontal" theme="dark" active-name="1">
      <div class="layout-logo">
        <router-link to="/"><img src="./assets/prism.png"></router-link>
      </div>
      <div class="layout-nav">
        <Menu-item name="1">
          <Icon type="ios-speedometer"></Icon>
          <router-link to="/dashboard">仪表盘</router-link>
        </Menu-item>
        <Menu-item name="2">
          <Icon type="ios-pie"></Icon>
          <router-link to="/report">报表</router-link>
        </Menu-item>
        <Menu-item name="3">
          <Icon type="ios-folder"></Icon>
          <router-link to="/db">数据库</router-link>
        </Menu-item>
      </div>
    </Menu>
    <div class="layout-content">
      <router-view></router-view>
    </div>
    <div class="layout-copy">
      2017 &copy; Prism
    </div>
  </div>
</template>
<script>
  export default {

  }
</script>
