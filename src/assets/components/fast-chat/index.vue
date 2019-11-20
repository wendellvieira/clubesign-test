<template>
	<div class='cnt-fast-chat'>
		<nav class="cnt-chat-msgs" ref='cntMsgs' :class='{"maxmize": statusConversation > 1}'>
			<div class="mark-height" ref='markHeight'>
				<div class="cnt-item-msg" v-for='(msg, id) in filterMensages'>
					<div v-if='is_visible(msg.event)' class="day-chat-conversation">
						{{ msg.event | date }}
					</div>
					<ItemMensage  :msg='msg'/>
				</div>
				<div class="clearfix"></div>
			</div>
		</nav>
		<SendMensage v-if='statusConversation == 1' :sc='statusConversation' :cid='cid' :to='to'/>
	</div>
</template>
<script>
	import SendMensage from './components/send-msg.vue'
	import ItemMensage from './components/item-msg.vue'
	import {mapState} from 'vuex'
	import {fDate} from 'js/tools'
	export default {
		name: "FastChatBase",
		props: ['cid', 'to' ],
		components: { SendMensage, ItemMensage },
		data(){
			return {
				lastDay: ''
			}
		},
		computed: {
			...mapState('wb-service', ['mensages', 'updates', "conversations"]),
			filterMensages(){
				return this.mensages.filter(item=> item.cid == this.cid)
			},
			is_visible: event => (event)=>{
				if( this.lastDay != fDate(event, "DD-MM-YY") ){
					this.lastDay = fDate(event, "DD-MM-YY")
					return true
				}else return false
			},
			statusConversation(){
				let c = this.conversations.find(item => item.cod == this.cid)
				if(c != null) return c.status
				else return 9
			}
		},
		filters: {
			date(val){
				return fDate(val, "dslg, DD de xmlg de YY")
			}
		},
		watch: {
			"updates": function(val){
				setTimeout(e=>{
					this.lastMsg()
				}, 100)
			}
		},
		methods: {
			length(){
				return this.filterMensages.length
			},
			lastMsg(){
				if( $(this.$refs.cntMsgs).is(':visible') ){
					let h = $(this.$refs.markHeight).height()
					$(this.$refs.cntMsgs).scrollTop(h)
					let not_received = this.filterMensages.filter( 
						item=> ( item.status == 2 || item.status == 1 ) && item.from == this.to 
					)
					this.$store.dispatch('wb-service/client_read', not_received)					
				}
				setTimeout(e=> {
					let total = this.filterMensages.filter(item=> item.status != 3 && item.from == this.to  )
					this.$emit('update:newMsgs', total.length)
				}, 500)
			}
		},
		mounted(){
			let self = this
			$(this.$refs.markHeight).height(function(){
				$(self.$refs.cntMsgs).scrollTop( $(this).height() )
			})
		}
	};
</script>
<style scoped>

	.day-chat-conversation {
		display: block;
	    text-align: center;
	    width: 300px;
	    background: #e1f9ff;
	    padding: 12px 0px;
	    border-radius: 5px;
	    box-shadow: 0px 1px 1px #cecece;
	    font-weight: 400;
	    margin: 0px auto;
	}
	.cnt-fast-chat {
		height: 270px;
	    display: block;
	    width: 100%;
	    max-width: 100%;
	    position: relative;
	    overflow: hidden;
	}
	.cnt-chat-msgs {
		display: block;
	    height: 218px;
	    overflow: auto;
	    padding: 0px 15px;
	}
	.cnt-chat-msgs::-webkit-scrollbar {
	    width: 5px;
	    background: #dddee2;
	    margin-right: 5px;
	}
	.cnt-chat-msgs::-webkit-scrollbar-thumb {
	    background: #44d4bd;
	    border-radius: 5px;
	}
	.cnt-chat-msgs::-webkit-scrollbar-track {
	    background-color: #dddee2;
	    border-radius: 5px;
	}
	.maxmize {
		height: 270px;
	}
</style>