<template>
    <div class="row">
        <div class="col-12">
            <div v-if="Object.keys(errors).length" class="alert alert-danger">
                Please check the form below for errors
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>
            <form v-on:submit="saveForm()">
                <div class="row">
                    <div class="col-12 form-group">
                        <input type="hidden" v-model="add_script.site_id" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 form-group">
                        <label class="control-label">Name</label>
                        <input type="text" v-model="add_script.name" class="form-control "
                               v-bind:class="{ 'is-invalid' : 'name' in Object(errors) }">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 form-group">
                        <label class="control-label">Body</label>
                        <textarea v-model="add_script.body" class="form-control"
                                  v-bind:class="{ 'is-invalid' : 'body' in Object(errors)  }"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 form-group">
                        <button class="btn btn-success">Add script</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12">
            <h3>Scripts {{ draggingInfo }}</h3>
            <div v-if="scripts.length">
                <draggable
                        :list="scripts"
                        :disabled="!enabled"
                        class="list-group"
                        ghost-class="ghost"
                        @start="dragging = true"
                        @end="dragging = false"
                >
                    <div class="list-group-item" v-for="script, index in scripts" :key="script.name">
                        <div class="row">
                            <div class="col-1 move first"></div>
                            <div class="col-4 move">{{ script.name }}</div>
                            <div class="col-5" v-if="script.slice"><span v-on:click="showMore(index)"
                                                                         class="body-preview text-primary">{{ script.excerpt  }}</span>
                            </div>
                            <div class="col-5" v-else>{{ script.body }}</div>
                            <div class="col-2">
                                <a class="btn btn-xs btn-danger" onclick="confirm('Delete this script')?true:false"
                                   v-on:click="replace(script.id, index)">Delete</a>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-xs btn-info" v-on:click="position()">Save position</a>
                </draggable>
            </div>
            <p v-else>
                No script. Add new!
            </p>
        </div>
        <div v-if="showModal" class="modal show bd-example-modal-lg" id="bodyModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Body full script</h5>
                        <button @click="showModal = false" type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="content.length">
                        <pre>
                        {{ content }}
                        </pre>
                    </div>
                    <div class="modal-footer">
                        <button @click="showModal = false" type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable';

    export default {
        props: ['currentUser', 'siteId'],
        components: {
            draggable
        },
        name: "simple",
        display: "Simple",
        order: 0,

        data() {
            return {
                enabled: true,
                scripts: [],
                dragging: false,
                add_script: {
                    site_id: this.siteId,
                    name: '',
                    body: '',
                },
                errors: [],
                showModal: false,
                saveScript: '',
            };
        },
        mounted() {
            const formData = new FormData();
            formData.append('site_id', this.siteId);

            axios.post('/api/scripts', formData)
                .then(response => {
                    this.scripts = response.data;
                    this.scripts = this.scripts.filter(function (item) {
                        let sliced = item.body.slice(0, 20);
                        if (sliced.length < item.body.length) {
                            sliced += '...';
                            item.slice = true;
                        }
                        item.excerpt = sliced;

                        return item;
                    });
                })
                .catch(function (e) {
                    console.log(e);
                });
        },
        computed: {
            draggingInfo() {
                return this.dragging ? "drag" : "";
            }
        },
        methods: {
            saveForm() {
                this.errors = {};
                this.saveScript = '';
                if (!this.add_script.name) {
                    this.errors.name = 'The name field is required.';
                }
                if (this.add_script.name.length>200) {
                    this.errors.name = 'The name may not be greater than 200 characters.';
                }
                if (!this.add_script.body) {
                    this.errors.body = 'The body field is required.';
                }

                event.preventDefault();

                var newScript = this.add_script;
                if (!Object.keys(this.errors).length) {
                    axios.post('/api/script/add', newScript)
                        .then(response => {
                            let item = response.data.body, slice,
                                excerpt = item.slice(0, 20);
                            if (excerpt.length < item.length) {
                                excerpt += '...';
                                slice = true;
                            } else {
                                slice = false;
                            }
                            this.scripts.unshift({
                                id: response.data.id,
                                name: response.data.name,
                                body: response.data.body,
                                excerpt: excerpt,
                                slice: slice
                            });
                            this.add_script.name = null;
                            this.add_script.body = null;

                        }).catch(function (e) {
                            console.log(e);
                        });
                }
            },

            replace: function (id, index) {
                const formData = new FormData();
                formData.append('id', id);

                axios.post('/api/script/delete', formData)
                    .then(response => {
                        this.scripts.splice(index, 1);
                    })
            },

            position: function () {
                const formData = new FormData();
                let newPosition = [];

                this.scripts.forEach(function (val) {
                    newPosition.push(val.id);
                });
                formData.append('newPosition', newPosition);

                axios.post('/api/script/position', formData)
                    .then(response => {
                        alert('Save position');
                    })
            },

            showMore: function (index) {
                this.content = this.scripts[index].body;
                this.showModal = true;
            },

        }
    };
</script>
<style scoped>
    .body-preview:hover {
        cursor: pointer
    }

    #bodyModal {
        display: block;
    }

    .modal-body {
        max-height: calc(80vh - 100px);
        overflow-y: auto;
    }

    .move.first:before {
        content: '+';
        position: relative;
        display: block;
        /*top: 10px;*/
        left: 15px;
    }

    .move:hover {
        cursor: move;
    }
</style>
