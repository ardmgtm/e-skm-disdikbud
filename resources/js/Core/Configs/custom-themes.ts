import Aura from '@primevue/themes/aura';
import { definePreset } from '@primevue/themes';

const customPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '#e3eafd',
            100: '#c7d4fa',
            200: '#a3b8f3',
            300: '#7a97ea',
            400: '#5676e0',
            500: '#3556d6',
            600: '#2743b7',
            700: '#1d3491',
            800: '#182a72',
            900: '#151f4d',
            950: '#0c1127'
        }
    }
});

export default customPreset;