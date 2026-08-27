import { watch } from 'vue';
import { router } from '@inertiajs/vue3';

export function useDebouncedFilter(urlOrRoute, filtersObj, options = {}) {
  const delay = options.delay || 300;
  const only = options.only || [];

  let timeout = null;

  watch(
    () => Object.keys(filtersObj).map(key => {
      const item = filtersObj[key];
      return item && typeof item === 'object' && 'value' in item ? item.value : item;
    }),
    () => {
      if (timeout) clearTimeout(timeout);
      timeout = setTimeout(() => {
        const queryParams = {};
        Object.keys(filtersObj).forEach(key => {
          const item = filtersObj[key];
          queryParams[key] = item && typeof item === 'object' && 'value' in item ? item.value : item;
        });

        router.get(
          urlOrRoute,
          queryParams,
          {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            ...(only.length ? { only } : {})
          }
        );
      }, delay);
    }
  );
}
