<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'createSite'}" class="btn btn-success">Add site</router-link>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Url</th>
                        <th>Name</th>
                        <th>Script</th>
                        <th style="width: 100px;"></th>
                        <th style="width: 100px;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="site, index in sites">
                        <td>{{ site.title }}</td>
                        <td>{{ site.body }}</td>
                        <td>
                            <div>
                                <h2>Script</h2>
                                <draggable class="drag-area" :list="tasksNotCompletedNew" :options="{animation:200, group:'status'}" :element="'article'" @add="onAdd($event, false)"  @change="update">
                                    <article class="card" v-for="(task, index) in tasksNotCompletedNew" :key="task.id" :data-id="task.id">
                                        <header>
                                            {{ task.title }}
                                        </header>
                                    </article>
                                </draggable>
                            </div>
                            <br><router-link :to="{name: 'createScript', params: {id: site.id}}" class="btn btn-success">Add script</router-link>
                        </td>
                        <td><router-link :to="{name: 'editSite', params: {id: site.id}}" class="btn btn-info">
                            Edit
                        </router-link></td>
                        <td><a href="#" class="btn btn-danger" v-on:click="deleteEntry(site.id, index)">Delete</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
	import VueDraggable from 'vue-draggable'

	export default {
		components: {
			VueDraggable
		},


		data: function () {
			return {
				sites: [],
				user: null,
				tasksNotCompletedNew:[
					{id: "1", title: "1"}, {id: "2", title: "2"}, {id: "3", title: "333"}
				]
			}
		},
		created () {
			this.fetchUser();
		},
		mounted() {
			var app = this;
			axios.get('/api/sites')
				.then(function (resp) {
					app.sites = resp.data;
				})
				.catch(function (resp) {
					console.log(resp);
				});
		},
		methods: {
			onAdd(event, status) {
				let id = event.item.getAttribute('data-id');
				axios.patch('/demos/tasks/' + id, {
					status: status
				}).then((response) => {
					console.log(response.data);
				}).catch((error) => {
					console.log(error);
				})
			},
			update() {
				this.tasksNotCompletedNew.map((task, index) => {
					task.order = index + 1;
				});

				this.tasksCompletedNew.map((task, index) => {
					task.order = index + 1;
				});

				let tasks = this.tasksNotCompletedNew.concat(this.tasksCompletedNew);

				axios.put('/demos/tasks/updateAll', {
					tasks: tasks
				}).then((response) => {
					console.log(response.data);
				}).catch((error) => {
					console.log(error);
				})
			},

			deleteEntry(id, index) {
				if (confirm("Do you want delete site?")) {
					var app = this;
					const formData = new FormData();
					formData.append('id', id);

					axios.post('/api/post/delete', formData)
						.then(function (resp) {
							app.sites.splice(index, 1);
						})
						.catch(function (resp) {
							alert("Could not delete site");
						});
				}
			},

            // now user
			fetchUser () {
				axios.get('/api/curentuser')
					.then(response => {
						this.user = response.data;
					})
					.catch(error => {
						alert('Something went wrong');
						console.error(error.response.data);
					});
			}
		}
	}
</script>

<style>
    .list {
        background-color: #26004d;
        border-radius: 3px;
        margin: 5px 5px;
        padding: 10px;
        width: 100%;
    }
    .list>header {
        font-weight: bold;
        color: white;
        text-align: center;
        font-size: 20px;
        line-height: 28px;
        cursor: grab;
    }
    .list article {
        border-radius: 3px;
        margin-top: 10px;
    }

    .list .card {
        background-color: #FFF;
        border-bottom: 1px solid #CCC;
        padding: 15px 10px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bolder;
    }
    .list .card:hover {
        background-color: #F0F0F0;
    }
    .drag-area{
        min-height: 10px;
    }
</style>
