<template lang="html">
    <div class="profile">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <img :src="user.avatar_url" class="img-circle">
                            <p class="lead">{{user.name}}</p>
                            <small class="text-muted">Software Architect</small>
                        </div>
                        <hr>
                        <p><strong>Contact details</strong></p>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-fw fa-envelope" aria-hidden="true"></i> {{user.email}}</li>
                            <li><i class="fa fa-fw fa-phone" aria-hidden="true"></i> 811123123</li>
                            <li><i class="fa fa-fw fa-birthday-cake" aria-hidden="true"></i> July 10, 1997</li>
                            <li><i class="fa fa-fw fa-calendar" aria-hidden="true"></i> July 10, 2017</li>
                            <li><i class="fa fa-fw fa-mortar-board" aria-hidden="true"></i> Tecnologico de Parral</li>
                            <li><i class="fa fa-fw fa-map-marker" aria-hidden="true"></i> Parral Chih.</li>
                            <li><i class="fa fa-fw fa-link" aria-hidden="true"></i> <a href="http://www.example.com" target="_blank">www.example.com</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active"><a href="#information" data-toggle="tab">Information</a></li>
                            <li><a href="#notes" data-toggle="tab">Notes</a></li>
                            <li><a href="#work" data-toggle="tab">Work</a></li>
                            <li><a href="#notifications" data-toggle="tab">Notifications</a></li>
                            <li><a href="#documents" data-toggle="tab">Documents</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="information">
                                <h6>Personal</h6>
                                <hr>
                                <h6>Contact</h6>
                                <hr>
                                <h6>Education</h6>
                                <hr>
                                <h6>Family</h6>
                                <hr>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="notes">...</div>
                            <div role="tabpanel" class="tab-pane" id="work">...</div>
                            <div role="tabpanel" class="tab-pane" id="notifications">...</div>
                            <div role="tabpanel" class="tab-pane" id="documents">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:[
        'user'
    ]
}
</script>
