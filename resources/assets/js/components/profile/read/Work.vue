<template lang="html">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <i class="fa fa-fw fa-briefcase" aria-hidden="true"></i> Work
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <dl>
                        <dd>Profession</dd>
                        <dt v-if="user.profile_work.profession" class="text-capitalize">
                            {{user.profile_work.profession.title}}
                        </dt>
                    </dl>
                    <dl>
                        <dd>Position</dd>
                        <dt v-if="user.profile_work.position" class="text-capitalize">
                            {{user.profile_work.position.title}}
                        </dt>
                    </dl>
                    <dl>
                        <dd>Department</dd>
                        <dt v-if="user.profile_work.department" class="text-capitalize">
                            {{user.profile_work.department.title}}
                        </dt>
                    </dl>
                    <dl>
                        <dd>Boss</dd>
                        <dt v-if="user.profile_work.boss">
                            {{user.profile_work.boss.name}}
                        </dt>
                    </dl>
                    <dl>
                        <dd>Starting from</dd>
                        <dt>{{user.profile_work.starting_from | moment('from')}}</dt>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user']
    }
</script>
