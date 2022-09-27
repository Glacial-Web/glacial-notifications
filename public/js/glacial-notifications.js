(function ($) {

    $(document).ready(function () {
        $('.gla-noti').each(function () {
            let $notification = $(this);
            this.announcer = new GlaNoti($notification);
        });
    });

})(jQuery);

function GlaNoti_Position() {
    this.spacer = false;
    this.normal_moved = false;

    this.sticky_offset = 0;
    this.sticky_group = false;
    this.sticky_elements = false;
}

function GlaNoti_State() {

    this.bars = [];
    this.top = new GlaNoti_Position();
    this.bottom = new GlaNoti_Position();
    this.on_scroll_bars = [];

    this.listen_on_scroll();

}

GlaNoti_State.prototype.add = function (bar) {
    let position = bar.props.position;

    let position_class = '.gla-noti-pos-top';

    this.bars.push(bar);

    this.add_spacer(position);
    if (!this[position].sticky_group) {
        this[position].sticky_group = jQuery(position_class + '.gla-noti-sticky');
    }


    if (bar.props.show_on == 'page_scroll') {
        this.on_scroll_bars.push(bar);
    }
}

GlaNoti_State.prototype.add_spacer = function (position) {

    if (!this[position].spacer) {
        let $spacer = jQuery('<div class="glacial-top-spacer"></div>');
        this[position].spacer = $spacer;
        if (position == 'top') {
            jQuery('body').prepend($spacer);
        } else {
            jQuery('body').append($spacer);
        }
    }
}

GlaNoti_State.prototype.update_offsets = function (position) {

    if (this[position].sticky_group) {
        this[position].sticky_offset = this[position].sticky_group.outerHeight();
        this[position].spacer.height(this[position].sticky_offset);

    }

}

GlaNoti_State.prototype.set_cookie = function (name, value, expiry_days, site_wide) {

    let expires = '';
    let path = '; path=/';

    if (expiry_days) {
        let date = new Date();
        date.setTime(date.getTime() + (expiry_days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }

    document.cookie = name + '=' + value + expires + path;

}

GlaNoti_State.prototype.get_cookie = function (name) {

    let name_eq = name + "=";
    let ca = document.cookie.split(';');

    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);

        if (c.indexOf(name_eq) == 0) {
            return c.substring(name_eq.length, c.length);
        }
    }

    return null;

}

GlaNoti_State.prototype.listen_on_scroll = function () {

    let self = this;
    let $ = jQuery;

    $(window).scroll(function () {
        let at = $(window).scrollTop();

        for (let i = 0; i < self.on_scroll_bars.length; i++) {
            let bar = self.on_scroll_bars[i];

            if (at >= bar.props.show_after_scroll) {
                if (!bar.is_shown) {
                    if (bar.can_show()) bar.show();
                }
            } else {
                if (bar.is_shown) {
                    bar.hide(false);
                }
            }
        }

    });

}

GlaNoti_State.prototype.adjust_fixed_elements = function () {

    let top = this['top'];

    if (!top.sticky_group) {
        return;
    }

    if (!top.sticky_elements) {
        let possible_stickies = document.querySelectorAll('div, header, nav');
        top.sticky_elements = [];

        for (let i = 0; i < possible_stickies.length; i++) {
            let element = possible_stickies[i];

            if (element.className.includes('gla-noti')) {
                continue;
            }

            let element_bound = element.getBoundingClientRect();
            let element_style = window.getComputedStyle(element, null);

            if (element_style.position === 'fixed' && element_style.display != 'none' && element_bound.top <= top['sticky_offset'] && element_bound.left == 0) {
                top.sticky_elements.push(element);
            }
        }

    }

    for (i = 0; i < top.sticky_elements.length; i++) {

        let element = top.sticky_elements[i];
        element.style.top = top['sticky_offset'] + 'px';
    }

}

GlaNoti_State.prototype.is_mobile = function () {
    return /Mobi|Android/i.test(navigator.userAgent);
}

window.glanoti_state = new GlaNoti_State();

function GlaNoti($el) {

    this.$el = $el;
    this.props = $el.data('props');
    this.is_shown = false;
    this.close_cookie = 'glacial_notification_closed';
    this.force_closed = false;

    glanoti_state.add(this);

    this.register_events();
    this.check_show();

}

GlaNoti.prototype.register_events = function () {

    let self = this;
    let $close_btn = this.$el.find('.gla-noti-close-button');

    if (this.props.close_content_click == 'yes') {
        $close_btn = $close_btn.add(this.$el.find('.gla-noti-inner a'));
    }

    if ($close_btn.length != 0) {
        $close_btn.on('click', function (e) {
            if (jQuery(this).attr('href') == '#') {
                e.preventDefault();
            }

            self.hide();

            if (self.props.show_on == 'page_scroll') {
                self.force_closed = true;
            }
        });
    }

}

GlaNoti.prototype.can_show = function () {

    if (this.props.display == 'custom') {
        return false;
    }

    let closed_cookie = glanoti_state.get_cookie(this.close_cookie);

    if (this.props.devices == 'mobile_only' && !glanoti_state.is_mobile()) {
        return false;
    }

    if (this.props.devices == 'desktop_only' && glanoti_state.is_mobile()) {
        return false;
    }

    if (this.props.cookie == 'yes' && closed_cookie) {
        return false;
    }

    if (this.force_closed) {
        return false;
    }

    return true;

}

GlaNoti.prototype.check_show = function () {

    let self = this;

    if (!this.can_show()) {
        return;
    }

    if (this.props.show_on == 'page_open') {
        self.show();
    } else if (this.props.show_on == 'duration') {
        setTimeout(function () {
            self.show();
        }, this.props.show_after_duration * 1000)
    }

}

GlaNoti.prototype.show = function () {
    let self = this;
    this.is_shown = true;

    this.animate('show', function () {
        self.after_show();
    });

}

GlaNoti.prototype.after_show = function () {

    let position = this.props.position;
    glanoti_state.update_offsets(position);
    glanoti_state.adjust_fixed_elements();

    if (this.props.auto_close != '0') {
        this.auto_close();
    }
}

GlaNoti.prototype.hide = function (set_cookie = true) {
    let self = this;
    this.is_shown = false;

    this.animate('hide', function () {
        self.after_hide(set_cookie);
    });
}

GlaNoti.prototype.after_hide = function (set_cookie = true) {

    let position = this.props.position;
    glanoti_state.update_offsets(position);
    glanoti_state.adjust_fixed_elements();

    let closed_duration = (this.props.closed_duration == '0') ? false : this.props.closed_duration;

    if (this.props.cookie == 'yes' && set_cookie) {
        glanoti_state.set_cookie(this.close_cookie, 1, closed_duration, true);
    }

}

GlaNoti.prototype.set_offset = function () {
    let position = this.props.position;
    let css_props = {};
    let offset = glanoti_state[position].offset_total;

    css_props[position] = offset + 'px';
    //this.$el.animate(css_props);

}

GlaNoti.prototype.auto_close = function () {
    let self = this;
    setTimeout(function () {
        self.hide();
    }, this.props.auto_close * 1000);
}

GlaNoti.prototype.animate = function (type, callback) {

    let animations = {
        'slide': ['slideDown', 'slideUp'],
        'fade': ['fadeIn', 'fadeOut']
    };

    let chosen = (type == 'show') ? this.props.open_animation : this.props.close_animation;
    let duration = 100;
    let animation = 'show';

    if (chosen == 'none') {
        animation = (type == 'show') ? 'show' : 'hide';
        duration = 0;
    } else {
        animation = (type == 'show') ? animations[chosen][0] : animations[chosen][1];
    }

    this.$el[animation](duration, callback);

}