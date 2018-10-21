<template>
    <div class="voter">
        <div class="total">
            {{ totalVotes }}
        </div>
        <div class="up" @click="voteUp()" :class="{'active': status === 1, 'disabled': !votable}">
            <i class="fa fa-arrow-up"></i>
            {{ upvotes + upDelta }}
        </div>
        <div class="down" @click="voteDown()" :class="{'active': status === -1, 'disabled': !votable}">
            <i class="fa fa-arrow-down"></i>
            {{ downvotes + downDelta }}
        </div>
    </div>
</template>

<script>
    export default {
        props: ['_status', '_upvotes', '_downvotes', '_votable', 'type', 'id'],
        data() {
            return {
                status: 0,
                upvotes: 0,
                downvotes: 0,
                votable: false,
                cancelSource: null
            }
        },
        mounted() {
            this.status = parseInt(this._status);
            this.upvotes = parseInt(this._upvotes);
            this.downvotes = parseInt(this._downvotes);
            this.votable = this._votable === 'true';

            // Initialize cancellation token
            this.cancelSource = axios.CancelToken.source();

            // calculate realVotes
            this.calculateRealVotes();
        },
        methods: {
            calculateRealVotes() {
                this.realVotes = this.upvotes - this.downvotes;

                if (this._status === '1') {
                    this.upvotes--;
                } else if (this._status === '-1') {
                    this.downvotes--;
                }
            },
            voteUp() {
                if (!this.votable) {
                    alert('Please log in to vote.');
                    return;
                };

                if (this.status === 0 || this.status === -1) {
                    this.status = 1;
                } else {
                    this.status = 0;
                }

                this.postStatus();
            },
            voteDown() {
                if (!this.votable) {
                    alert('Please log in to vote.');
                    return;
                };

                if (this.status === 0 || this.status === 1) {
                    this.status = -1;
                } else {
                    this.status = 0;
                }

                this.postStatus();
            },
            postStatus() {
                this.cancelSource.cancel('Changing vote');

                axios.post(`/vote/${this.type}?id=${this.id}&status=${this.status}`, {
                    cancelToken: this.cancelSource.token
                }).catch(function(thrown) {
                    if (!axios.isCancel(thrown)) {
                        alert('Could not send vote information to the server');
                    }
                });
            }
        },
        computed: {
            totalVotes() {
                return this.upvotes - this.downvotes + this.upDelta - this.downDelta;
            },
            upDelta() {
                return this.status === 1 ? 1 : 0;
            },
            downDelta() {
                return this.status === -1 ? 1 : 0;
            }
        }
    };
</script>

<style scoped>
    .voter {
        display: flex;
        /*width: 200px;*/
        justify-content: center;
    }

    .voter > .total {
        padding: 0 6px;
        margin-right: 6px;
        min-width: 30px;
        text-align: center;
        height: 22px;
        border: 1px solid #e6e6e6;
        border-radius: 4px;
    }

    .voter > .up, .voter > .down {
        background-color: #ececec;
        padding: 0 6px;
        min-width: 50px;
        display: flex;
        justify-content: space-around;
        height: 22px;
        color: #6b6b6b;
        cursor: pointer;
    }

    .voter > .up.disabled, .voter > .down.disabled {
        cursor: not-allowed;
    }

    .voter > .up > i:before, .voter > .down > i:before {
        display: inline-block;
        margin-top: 4px;
    }

    .voter > .up {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        border: 1px solid #e6e6e6;
        border-right: 1px solid #e6e6e6;
    }

    .voter > .down {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
        border: 1px solid #e6e6e6;
    }

    .voter > .up.active {
        color: #039be5;
    }

    .voter > .down.active {
        color: #ba68c8;
    }

    .voter > .up:hover:not(.active):not(.disabled), .voter > .down:hover:not(.active):not(.disabled) {
        color: #000;
    }

    .far.fa-arrow-up:before {
        content: '\f062';
    }

    .far.fa-arrow-down:before {
        content: '\f063';
    }
</style>
