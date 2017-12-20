<template>
    <div class="system-notification" :class="itemClass">

        <!-- progress -->
        <div
                v-if="showProgress"
                :class="['system-notify__progress', notify.cssClass]"
                :style="{animationDuration: notify.timeout + 'ms', animationPlayState: animationState}"
        ></div>

        <!-- content -->
        <div class="system-notify__content">
            <h4 class="system-notify__title" v-if="notify.title">{{ notify.title }}</h4>
            <div class="system-notify__message" v-html="message"></div>
            <div class="system-notify__buttons" v-if="buttons">
                <button
                        v-for="(btn, i) in buttons"
                        :key="i"
                        class="btn system-notify__btn"
                        @click="onButtonClick(btn)"
                >{{ btn.text }}</button>
            </div>
        </div>

        <!-- close button -->
        <button
                class="system-notify__close"
                type="button"
                :title="$lang.NOTIFY_CLOSE"
                :aria-label="$lang.NOTIFY_CLOSE"
                @click="$emit('close')"
        >
            <svg width="25" height="25" viewBox="0 0 25 25">
                <path fill="none" stroke="currentColor" stroke-width="1.06" d="M22,22 L4,4 M22,4 L4,22"></path>
            </svg>
        </button>
    </div>
</template>

<script>
  import { stripScripts } from '../modules/helper'
  import axios from 'axios'

  export default {

    name: 'system-notify',

    props: {

      notify: {
        type: Object,
        required: true
      }
    },


    data() {
      return {
        animationState: 'running'
      }
    },


    computed: {

      itemClass() {
        return [
          this.notify.status ? ('is-' + this.notify.status ): '',
          this.themeClasses && 'theme-' + this.themeClasses.join(' theme-')
        ]
      },

      themeClasses() {
        let theme = this.notify.theme
        return theme && ( Array.isArray(theme) ? theme : theme.split(',') ).map( cls => cls.trim() )
      },

      showProgress() {
        return this.themeClasses && this.themeClasses.indexOf('progress') > -1 && this.notify.timeout
      },

      message() {
        return stripScripts(this.notify.message)
      },

      buttons() {
        let btn = this.notify.button
        if ( btn ) {
          return Array.isArray(btn) ? btn : [btn]
        } else {
          return false
        }
      }
    },


    methods: {

      onButtonClick(btn) {

        this.animationState = 'paused'
        clearTimeout(this.notify._timeout)
        axios({
          method: btn.method,
          url: btn.url,
          data: btn.data
        }).then( response => {
          if (response.success) {
            if ( res.data.message ) {
              this.emit(`notify::${this.notify.container}:show`, {
                message: res.data.message
              });
              // call sended if exists
              if ( typeof btn.sended === 'function' ) {
                btn.sended.call(this)
              }
              // remove notify
              this.$emit('close')
            }
          }
        });
      }
    }
  }
</script>
