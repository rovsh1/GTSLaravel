const cookieName = 'timezone';

export default {
    timezone: null,
    domain: null,
    setTimezone: function (reload) {
        this.timezone = this.getCookie(cookieName);
        if (this.timezone)
            return;

        this.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        this.setCookie(cookieName, this.timezone, 30);

        if (reload)
            location.reload();
    },
    setCookie: function (cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        document.cookie = cname + "=" + cvalue
            + "; expires=" + d.toUTCString()
            + '; path=/'
            + (this.domain ? '; domain=' + this.domain : '');
    },
    getCookie: function (cname) {
        const name = cname + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];

            while (c.charAt(0) == ' ')
                c = c.substring(1);

            if (c.indexOf(name) == 0)
                return c.substring(name.length, c.length);
        }

        return null;
    }
};
