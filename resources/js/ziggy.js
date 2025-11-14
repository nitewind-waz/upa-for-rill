const Ziggy = {
    url: 'http://localhost',
    port: null,
    defaults: {},
    routes: {
        home: { uri: '/', methods: ['GET', 'HEAD'] },
        dashboard: { uri: '/dashboard', methods: ['GET', 'HEAD'] },
        // ...
    },
};

export { Ziggy };