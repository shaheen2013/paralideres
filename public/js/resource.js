new Vue({    el: '#app',    data: {        asset: window.asset,        base_url: window.base_url,        api_url: window.api_url,        userLike: userLike,        checkedAnswer: 0,        poll: [],        pollResult: false    },    methods: {        givenResourceLike: function (resource_id) {            var THIS = this;            axios.post(THIS.base_url + THIS.api_url + 'resources/' + resource_id + '/like')                .then(function (response) {                    if (response.data.status == 'like') {                        THIS.userLike = true;                    }                    else if (response.data.status == 'unlike') {                        THIS.userLike = false;                    }                });        },        getLastPoll: function () {            var THIS = this;            axios.get(THIS.asset + THIS.api_url + 'polls/last').then(function (response) {                THIS.poll = response.data.data;            });        },        votePoll: function () {            var THIS = this;            var formID = document.querySelector('#votePollSideBar');            var formData = new FormData(formID);            this.$common.loadingShow(0);            axios.post(THIS.base_url + THIS.api_url + 'polls/' + THIS.poll.id + '/vote', {                'option': formData.get('poll_option')            }).then(function (response) {                if(response.data.data.status === 2000){                    THIS.$common.loadingHide(0);                    THIS.pollResult = true;                    THIS.poll = response.data.data.poll;                    THIS.$common.showMessage(response.data);                    THIS.checkedAnswer = formData.get('poll_option');                } else if(response.data.data.status === 3000){                    THIS.$common.loadingHide(0);                    THIS.pollResult = true;                    THIS.poll = response.data.data.poll;                    THIS.$common.showMessage(response.data);                    THIS.checkedAnswer = response.data.data.has;                }            }).catch(function (response) {                this.$common.loadingHide(0);                if (error.response.status == 500 && error.response.data.code == 500) {                    THIS.$common.showMessage(error);                }            });        },    },    created: function () {        this.getLastPoll();    }});