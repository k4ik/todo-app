<template>
  <main>
    <div class="container">
      <form @submit.prevent="addTask">
        <input type="text" placeholder="Insert task here..." v-model="newTask" />
      </form>
      <div class="row"></div>
      <div class="container-content" v-for="task in tasks" :key="task.id">
        <div class="task-container">
          <input type="checkbox" v-model="task.completed" />
          <span class="task" :class="{ completed: task.completed }">{{ task.task }}</span>
          <button class="delete-button" @click="deleteTask(task.id)">
            <img src="../assets/images/trash.png" alt="trash icon" />
          </button>
        </div>
        <div class="row"></div>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  data() {
    return {
      tasks: [],
      newTask: "",
    };
  },
  mounted() {
    this.fetchTasks();
  },
  methods: {
    fetchTasks() {
      fetch("http://localhost:8000/tasks.php")
        .then((response) => response.json())
        .then((data) => {
          this.tasks = data.map(task => ({ ...task, completed: false }));
        });
    },
    addTask() {
      fetch("http://localhost:8000/tasks.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ task: this.newTask }),
      })
        .then(() => {
          this.fetchTasks();
          this.newTask = "";
        })
        .catch((error) => {
          console.error("Error adding task:", error);
        });
    },
    deleteTask(id) {
      fetch(`http://localhost:8000/tasks.php`, {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: id }),
      })
        .then(response => {
          if (response.ok) {
            this.fetchTasks();
          } else {
            throw new Error("Failed to delete task");
          }
        })
        .catch(error => {
          console.error("Error deleting task:", error);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../assets/scss/variables";

main {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: $bg-color;
  font-family: Arial, sans-serif;
  height: 100vh;
}

.container {
  max-width: 405px;
  width: 100%;
  padding: 30px;
  background-color: $bg-color2;
  border-radius: 32px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

input[type="text"] {
  width: 100%;
  padding: 10px;
  border-radius: 5px;
  font-size: 16px;
  outline: none;
  border: none;
  background: transparent;
  text-align: center;
}

.row {
  background-color: $row-color;
  height: 1px;
  width: 100%;
  margin-top: 17px;
}

.container-content {
  padding-top: 20px;
}

.task-container {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  justify-content: space-between;
}

input[type="checkbox"] {
  margin-right: 10px;
  width: 24px;
  height: 24px;
}

.delete-button {
  background: none;
  border: none;
  cursor: pointer;
}

.delete-button img {
  width: 24px;
  height: 24px;
  opacity: 0.5;
}

.delete-button img:hover {
  opacity: 1;
}

.completed {
  text-decoration: line-through;
  color: grey;
}
</style>