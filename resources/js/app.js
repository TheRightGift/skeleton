/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import { defineAsyncComponent } from "vue";

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});


app.component('landing-component', defineAsyncComponent(() => import('./components/Landing.vue')));
app.component('login-component', defineAsyncComponent(() => import('./components/auth/Login.vue')));
app.component('dashboard-component', defineAsyncComponent(() => import('./components/Dashboard.vue')));
app.component('tip-component', defineAsyncComponent(() => import('./components/TipPage.vue')));

// app.component('test-classroom-component', defineAsyncComponent(() => import('./components/TestClasses.vue')));
// app.component('test-zoom-component', defineAsyncComponent(() => import('./components/zoom/TestZoom.vue')));

// app.component('pricing-component', defineAsyncComponent(() => import('./components/Pricing.vue')));
// app.component('explore-component', defineAsyncComponent(() => import('./components/ExploreCourses.vue')));
// app.component('privacy-component', defineAsyncComponent(() => import('./components/PrivacyComponent.vue')));
// app.component('cookie-component', defineAsyncComponent(() => import('./components/CookieComponent.vue')));
// app.component('how-it-works-component', defineAsyncComponent(() => import('./components/HowItWorksComponent.vue')));

// app.component('signup-component', defineAsyncComponent(() => import('./components/auth/Signup.vue')));
// app.component('login-component', defineAsyncComponent(() => import('./components/auth/Login.vue')));
// app.component('forgotpassword-component', defineAsyncComponent(() => import('./components/auth/ForgotPassword.vue')));

// app.component('tut-dashboard-component', defineAsyncComponent(() => import('./components/tutor/Dashboard.vue')));
// app.component('tut-schedule-component', defineAsyncComponent(() => import('./components/tutor/Schedule.vue')));
// app.component('tut-earning-component', defineAsyncComponent(() => import('./components/tutor/Earnings.vue')));
// app.component('tut-message-component', defineAsyncComponent(() => import('./components/tutor/Message.vue')));
// app.component('tut-setting-component', defineAsyncComponent(() => import('./components/tutor/Setting.vue')));

// app.component('guardian-onboarding-component', defineAsyncComponent(() => import('./components/onboarding/Guardian.vue')));
// app.component('guardian-home-component', defineAsyncComponent(() => import('./components/guardian/Home.vue')));
// app.component('contactus-component', defineAsyncComponent(() => import('./components/ContactUs.vue')));
// app.component('guardian-setting-component', defineAsyncComponent(() => import('./components/guardian/Setting.vue')));
// app.component('guardian-reschedule-approve-component', defineAsyncComponent(() => import('./components/guardian/RescheduleApproval.vue')));

// app.component('kid-home-component', defineAsyncComponent(() => import('./components/kid/Home.vue')));
// app.component('kid-dashboard-component', defineAsyncComponent(() => import('./components/kid/Dashboard.vue')));
// app.component('kid-classroom-component', defineAsyncComponent(() => import('./components/kid/Classroom.vue')));
// app.component('kid-community-component', defineAsyncComponent(() => import('./components/kid/Community.vue')));
// app.component('kid-messages-component', defineAsyncComponent(() => import('./components/kid/Messages.vue')));
// app.component('kid-quiz-component', defineAsyncComponent(() => import('./components/kid/Quiz.vue')));
// app.component('kid-setting-component', defineAsyncComponent(() => import('./components/kid/Setting.vue')));

// // app.component('live-stream-component', defineAsyncComponent(() => import('./components/agora/Streamer.vue')));
// app.component('classroom-component', defineAsyncComponent(() => import('./components/Classroom.vue')));


// app.component('admin-dashboard-component', defineAsyncComponent(() => import('./components/admin/Dashboard.vue')));
// app.component('admin-tutors-component', defineAsyncComponent(() => import('./components/admin/Tutor/Tutors.vue')));
// app.component('admin-student-component', defineAsyncComponent(() => import('./components/admin/Students.vue')));
// app.component('admin-analytics-component', defineAsyncComponent(() => import('./components/admin/Analytics.vue')));
// app.component('admin-mail-component', defineAsyncComponent(() => import('./components/admin/Mail.vue')));
// app.component('admin-assessment-component', defineAsyncComponent(() => import('./components/admin/Assessments.vue')));
// app.component('admin-guardian-component', defineAsyncComponent(() => import('./components/admin/Guardian.vue')));
// app.component('request-testclass-component', defineAsyncComponent(() => import('./components/admin/TestClassRequests.vue')));
// app.component('admin-classes-component', defineAsyncComponent(() => import('./components/admin/Classes.vue')));
// app.component('admin-salary-component', defineAsyncComponent(() => import('./components/admin/Salary.vue')));
// app.component('admin-marketing-component', defineAsyncComponent(() => import('./components/admin/MarketingMail.vue')));

// https://rollupjs.org/guide/en/#output-manualchunks
// https://rollupjs.org/guide/en/#output-chunkfile

// build.rollupOptions.output.manualChunks = {
//     "admin-tutors-component": ['./resources/js/components/admin/Tutor/Tutors.vue'],
//     "admin-student-component": ['./resources/js/components/admin/Students.vue'],
//     "admin-analytics-component": ['./resources/js/components/admin/Analytics.vue'],
//     "admin-task-component": ['./resources/js/components/admin/Tasks.vue'],
//     "admin-assessment-component": ['./resources/js/components/admin/Assessments.vue'],

//     "live-stream-component": ['./resources/js/components/agora/Streamer.vue'],

// };

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');