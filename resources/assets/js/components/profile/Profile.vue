<template lang="html">
    <div class="profile">
        <div class="row">
            <div class="col-sm-3 profile-sidebar">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <img :src="user.avatar_url" class="img-circle">
                            <p class="lead">{{user.name}}</p>
                            <small class="text-muted">Software Architect</small>
                        </div>
                        <hr>
                        <ul class="nav nav-pills nav-stacked">
                            <li class="active">
                                <a href="#information" data-toggle="tab">
                                    <i class="fa fa-fw fa-envelope" aria-hidden="true"></i> Information
                                </a>
                            </li>
                            <li><a href="#work" data-toggle="tab">Work</a></li>
                            <li><a href="#notes" data-toggle="tab">Notes</a></li>
                            <li>
                                <a href="#notifications" data-toggle="tab">
                                    <i class="fa fa-fw fa-envelope" aria-hidden="true"></i> Notifications
                                </a>
                            </li>
                            <li><a href="#documents" data-toggle="tab">Documents</a></li>
                            <li><a href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 profile-content">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="information">
                        <profile-information :user="user"></profile-information>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="work">...</div>
                    <div role="tabpanel" class="tab-pane" id="notes">...</div>
                    <div role="tabpanel" class="tab-pane" id="notifications">
                        <notifications></notifications>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="documents">...</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import profileInformation from './profileInformation.vue'

export default {
    props: [
        'user'
    ],
    components: {
        profileInformation
    }
}
</script>
