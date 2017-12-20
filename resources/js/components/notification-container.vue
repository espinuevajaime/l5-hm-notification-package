<template>
    <transition-group
            v-if="stackDir"
            tag="ul"
            name="notification"
            class="system-notify-container"
            :class="['is-' + name, 'dir-' + stackDir, {'has-children': stackArr.length}]"
            aria-live="polite"
            aria-relevant="additions"
    >
        <li
                v-for="item in stackArr"
                class="system-notify-container__item"
                :key="item.id"
        >
            <slot v-bind="item" :remove="() => remove(stackArr[0].id)">
                <system-notification
                        :notify="item"
                        @close="remove(item.id)"
                />
            </slot>
        </li>
    </transition-group>

    <!-- single item -->
    <div v-else
         class="system-notify-container"
         :class="['is-' + name, {'has-children': stackArr.length}]"
         aria-live="polite"
         aria-relevant="additions"
    >
        <slot v-bind="stackArr[0]" :remove="() => remove(stackArr[0].id)">
            <transition name="notify" mode="out-in">
                <system-notification :key="stackArr[0].id"
                               v-if="stackArr[0]"
                               :notify="stackArr[0]"
                               @close="remove(stackArr[0].id)"
                />
            </transition>
        </slot>
    </div>

</template>

<script>
  import config from '../modules/config'
  import SystemNotification from './system-notification.vue'

  let _notifyId = 0

  export default {
    name: 'system-notify-container',
    components: { SystemNotification },
    props: {
      name: {
        type: String,
        required: true
      },
      stack: {
        type: [String, Boolean],
        default: 'bottom'
      },

      config: Object,

      notification: [Array, Object]
    },
    data() {
      return {
        stackArr: []
      }
    },
    computed: {
      stackDir() {
        switch ( this.stack ) {
          case 'top':
            return 'top'
            break;
          case 'false':
          case false:
            return false
            break;
          default:
            return 'bottom'
            break;
        }
      }
    },
    methods: {
      add(item) {
        try {
          if ( this.stackDir === false ) {
            // remove timeout
            if ( this.stackArr.length ) {
              clearTimeout(this.stackArr[0]._timeout)
            }
            // quickly replace all stackArr
            this.stackArr = [ item ]
          } else {
            this.stackArr[ this.stackDir === 'top' ? 'unshift' : 'push' ](item)
          }
          if ( item.timeout && typeof item.timeout === 'number' ) {
            item._timeout = setTimeout(() => { this.remove(item.id) }, item.timeout)
          }
        } catch(e) {
           console.log(e)
        }
      },
      remove(id) {
        if ( typeof id === 'undefined' ) return

        let i = this.stackArr.findIndex( item => item.id === id )

        if ( i > -1 ) {
          clearTimeout(this.stackArr[i]._timeout)
          this.stackArr.splice(i, 1)
          return true
        }
        return false
      },
      _createNotify(data) {
        _notifyId += 1

        return Object.assign({}, this._config, this.config, data, { id: _notifyId, container: this.name })
      },
      _showHandler(event) {
        let item = this._createNotify(event.detail)
        this.add(item)
        return item.id
      },
      _hideHandler(event) {
        return this.remove(event.detail)
      }
    },


    beforeMount() {
      this._config = Object.assign(config)
    },

    mounted() {
      // initial
      if ( this.notify ) {
        let _notifies = Array.isArray(this.notify) ? this.notify.slice() : [Object.assign({}, this.notify)]
        _notifies.forEach( notify => {
          notify = this._createNotify(notify)
          this.add(notify)
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
    $gap: 20px;
    .system-notify-container {
        list-style: none;
        padding: 0;
        margin: 0;

        &[class*="position-"] {
            position: fixed;

            max-height: 90vh; // no calc fallback
            max-height: 'calc(100vh - %s)' % ($gap * 2);

            max-width: 90%; // no calc fallback
            max-width: 'calc(100% - %s)' % ($gap * 2);
            // overflow: auto
            z-index: 100;
        }

        &.position-top-right {
            right: $gap;
            top: $gap;
        }

        &.position-top-left {
            left: $gap;
            top: $gap;
        }

        &.position-bottom-right {
            right: $gap;
            bottom: $gap;
        }

        &.position-bottom-left {
            left: $gap;
            bottom: $gap;
        }

        &.position-top-right {
            right: $gap;
            top: $gap;
        }

        &.position-top-center {
            left: 50%;
            top: $gap;
            transform: translateX(-50%);
        }

        &.position-bottom-center {
            left: 50%;
            bottom: $gap;
            transform: translateX(-50%);
        }

    }
</style>
