import './bootstrap';
import Alpine from 'alpinejs';

import.meta.glob(['../../public/storage/**']);
import.meta.glob(['../../public/img/**']);

window.Alpine = Alpine;

Alpine.start();
