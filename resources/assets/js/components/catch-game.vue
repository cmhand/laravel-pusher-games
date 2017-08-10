<style lang="scss">
    html,body {
        padding: 0;
        margin: 0;
        background-color: #efefef;
    }
    .game-container {
        width: 900px;
        height: 500px;
        margin: 0 auto;
        margin-top: 50px;
        border: 1px solid #ccc;
        background-color: #fff;
        position: relative;
    }
    .block {
        height: 25px;
        width: 25px;
        background-color: #000;
        position: absolute;
    }
    .guest {
        background-color: #f00;
    }
</style>
<template>
    <div>
        <div class="game-container">
            <div :style="{'top': top, 'left': left}" class="block"></div>
            <div v-for="player of players" :style="{'top': player.top, 'left': player.left}" class="block guest"></div>
        </div>
        <button @click="whisper()" type="button">Click</button>
    </div>
</template>

<script>
    export default {
        created() {
            this.id = this.getID();
            this.style = {
                top: this.getRandomInteger(0, 19) * 25,
                left: this.getRandomInteger(0, 35) * 25
            }
            Echo.private('chat')
                .listenForWhisper('typing', (w) => {
                    console.log(w);
                });
            Echo.channel('game')
                .listen('.moved', this.moved)
                .listen('.joined', (p) => {
                    console.log('Joined: ', p);
                    let joinedAlready = false;
                    for (let player of this.players) {
                        if (player.id === p.id) {
                            joinedAlready = true;
                        }
                    }
                    if (!joinedAlready && p.id !== this.id) {
                        this.players.push(p);
                        this.joined();
                    }
                });

            window.addEventListener('keyup', this.move);
            this.joined();
        },
        data() {
            return {
                message: "hi",
                style: {
                    top: 0,
                    left: 0,
                },
                id: 0,
                players: [],
            }
        },
        computed: {
            top() {
                return this.style.top + 'px';
            },
            left() {
                return this.style.left + 'px';
            }
        },
        methods: {
            whisper() {
                Echo.private('chat')
                    .whisper('typing', {'whispered': 'yes!'});
            },
            moved(e) {
                for (let i = 0; i < this.players.length; i++) {
                    if (this.players[i].id == e.id) {
                        this.players.splice(i, 1);
                        this.players.push(e);
                    }
                }
            },
            move(e) {
                if (e.keyCode == 37) {
                    this.moveLeft();
                } else if (e.keyCode == 38) {
                    this.moveUp();
                } else if (e.keyCode == 39) {
                    this.moveRight();
                } else if (e.keyCode == 40) {
                    this.moveDown();
                }
                axios.post('/api/moves', {
                    id: this.id,
                    left: this.left,
                    top: this.top,
                });
            },
            joined() {
                axios.post('/api/joined', {
                    id: this.id,
                    top: this.top,
                    left: this.left
                });
            },
            moveLeft() {
                if (this.style.left > 0) {
                    this.style.left -= 25;
                }
            },
            moveUp() {
                if (this.style.top > 0) {
                    this.style.top -= 25;
                }
            },
            moveRight() {
                if (this.style.left < 900) {
                    this.style.left += 25;
                }
            },
            moveDown() {
                if (this.style.top < 500) {
                    this.style.top += 25;
                }
            },
            getID() {
                return Math.random().toString(36).substr(2, 10);
            },
            getRandomInteger(min, max) {
              min = Math.ceil(min);
              max = Math.floor(max);
              return Math.floor(Math.random() * (max - min)) + min;
            }
        }
    }
</script>
