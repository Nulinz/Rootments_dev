importScripts('https://www.gstatic.com/firebasejs/11.4.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/11.4.0/firebase-messaging-compat.js');

const firebaseConfig = {
    apiKey: "AIzaSyDVdH_PbNRhl2YGMPronbaklLPbZCPyT4w",
    authDomain: "rootments-app.firebaseapp.com",
    projectId: "rootments-app",
    storageBucket: "rootments-app.firebasestorage.app",
    messagingSenderId: "406832035732",
    appId: "1:406832035732:web:0564580a464ee6f336c181"
  };

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon,
    };

   return self.registration.showNotification(notificationTitle, notificationOptions);
});


// if ('serviceWorker' in navigator) {
//     navigator.serviceWorker.register('/firebase-messaging-sw.js')
//         .then((registration) => {
//             console.log('Service Worker registered with scope:', registration.scope);

//             //FCM logic here.

//         }).catch((error) => {
//             console.error('Service Worker registration failed:', error);
//         });
// }

// messaging.onBackgroundMessage(function(payload) {
//     console.log('[firebase-messaging-sw.js] Received background message ', payload);
//     const notificationTitle = payload.notification.title;
//     const notificationOptions = {
//         body: payload.notification.body,
//         icon: payload.notification.icon,
//     };

//      return self.registration.showNotification(notificationTitle, notificationOptions);

//     // return messaging;
// });
