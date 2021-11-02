<template>
  <el-tabs v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="User" name="first">User</el-tab-pane>
    <el-tab-pane label="Config" name="second">Config</el-tab-pane>
    <el-tab-pane label="Role" name="third">Role</el-tab-pane>
    <el-tab-pane label="Task" name="fourth">Task</el-tab-pane>
  </el-tabs>
</template>

<script>
  export default {
    name: 'Home',
    setup(){
      const handleClick = function (tab, event) {
        console.log(tab, event)
      }

      return {
        handleClick
      }
    }
  }
</script>