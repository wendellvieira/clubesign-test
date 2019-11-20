<template>
	<ul class="item-msg" :class='{"right" : myId == msg.from}'>
		<div class="direct-chat-msg">				
			<span>{{msg.data}}</span>
			<div class="time">{{ msg.event | data }}</div>
			<i v-if='msg.status == 0' class="fal fa-spin fa-spinner"></i>
			<i v-else-if='msg.status == 1' class="fal fa-check"></i>
			<i v-else-if='msg.status == 2' class="fal fa-check alt"></i>
			<svg class='icon-received' v-else viewBox="0 0 448 512">
				<path d="M444.96 159l-12.16-11c-2.03-2.67-4.72-4-8.11-4s-6.08 1.33-8.11 4L131.77 428 31.42 329c-2.03-2.67-4.72-4-8.11-4s-6.08 1.33-8.11 4L3.04 340C1.01 342.67 0 345.67 0 349s1.01 6 3.04 8l120.62 119c2.69 2.67 5.57 4 8.62 4s5.92-1.33 8.62-4l304.07-300c2.03-2 3.04-4.67 3.04-8s-1.02-6.33-3.05-9zM127.17 284.03c2.65 2.65 5.48 3.97 8.47 3.97s5.82-1.32 8.47-3.97L365.01 63.8c1.99-2 2.99-4.65 2.99-7.96s-1-6.29-2.99-8.94l-11.96-10.93c-1.99-2.65-4.64-3.97-7.97-3.97s-5.98 1.32-7.97 3.97L135.14 236.34l-72.25-72.03c-1.99-2.65-4.64-3.97-7.97-3.97s-5.98 1.32-7.97 3.97l-11.96 10.93C33 177.89 32 180.87 32 184.18s1 5.96 2.99 7.95l92.18 91.9z"/>
			</svg>
		</div>
		<div class="clearfix"></div>				
	</ul>	
</template>
<script>
	import {fDate} from 'js/tools'
	import {mapState} from 'vuex'

	export default {
		name: "itemMsg",
		props: ['msg'],
		computed: {
			...mapState("application", {
				myId: s=> s.auth.cod
			})
		},
		filters: {
			data(val){
				// let moment = new Date()
				// let msg_data = new Date(val)
				// if( moment.getMinutes() <= msg_data.getMinutes() + 5 )
				// console.log(val)
				return fDate(val, "HH:II")
			}
		}
	};
</script>
<style scoped>
	.icon-received {
		fill:#83cde9;
		width: 10px;
	    height: 11px;
	    float: left;
	    margin: 5px 0px 0px 8px;
	}
	.right .icon-received {
		fill: #000000;	
	}

	.fal {
		color: #bdbdbd;	
	}
	.right .fal {
		color: #ffffff;	
	}

	.alt {
		color: #83cde9;
	}
	.right .alt {
		color: #000000;	
	}

	.item-msg {
		display: block;
	    width: 100%;
	    margin-bottom: 5px;
	    padding: 0px 0px 0px 0px;
	}
	.direct-chat-msg {
		max-width: 80%;
		background: #fff;
	    float: left;
	    display: flex;
	    padding: 9px;
	    border-radius: 4px;
	    box-shadow: 1px 1px 1px #cecece;
	}
	.right .direct-chat-msg {
		background-color: #3ec2b1;
		color: #fff;
		float: right;
	}
	.direct-chat-msg span {}
	.direct-chat-msg .time {
		    margin-left: 15px;
   		 font-weight: bolder;
	}
	.direct-chat-msg i {
	    margin: 3px 0 0px 10px;
	}
	.chat-mensage {
		background-color: #e8ebec;
   		padding: 15px;
	}
</style>