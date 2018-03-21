<template>
    <div class="modal right fade" id="modalEdit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Edit Invoice</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group" :class="{'has-error': error.description}">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control input-lg" placeholder="Description" required v-model="invoice.description">
                                </textarea>
                                <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                            </div>
                        </div>
                        <div class="form-group" :class="{'has-error': error.status}">
                            <label class="col-sm-3 control-label required">Invoice Status</label>
                            <div class="col-sm-9" v-if="list.statuses">
                                <multiselect v-model="invoice.status" label="name" :options="list.statuses" :placeholder="invoice.status ? invoice.status.name:'Select a status'" :multiple="false"></multiselect>
                                <span class="help-block" v-if="error.status">{{error.status[0]}}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" @click="destroy()">
                        <i class="mdi mdi-delete mdi-lg"></i> Delete
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="update">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  computed: {},
  mounted () {},
  data () {
    return {}
},
  methods: {},
}
</script>