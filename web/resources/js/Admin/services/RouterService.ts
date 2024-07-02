import {router} from "@inertiajs/vue3";
import {type NotificationType, NotifyService} from "~/services/NotifyService";
interface Params {
    onSuccess?: (page) => void,
    onError?: (page) => void,
    onFinish?: (page) => void,
    params?: any,
    preserveScroll?: boolean,
    offNotification?: boolean
}

type MethodType = 'get' | 'post' | 'put' | 'patch' | 'delete';
export class RouterService {

    static get(route: string, params: Params = { offNotification: false}) {
        this.send('get', route, params)
    }

    static delete(route: string, params: Params = { offNotification: false}) {
        router.delete(route, this.getOptions(params))
    }

    static post(route: string, params: Params = { offNotification: false}) {
        this.send('post', route, params)
    }

    private static send(type: MethodType, route: string, params: Params = { offNotification: false} ) {
        router[type](route, params?.params, this.getOptions(params))
    }

    static getOptions(params: Params = { offNotification: false} ) {
        return {
        ...(params.preserveScroll && { preserveScroll: params.preserveScroll}),
            onSuccess: (page: any) => {
                params.onSuccess?.(page);
                if (!params.offNotification) {
                    for (let [type, info] of Object.entries(page.props.flash)) {
                        if (info && (info as String).length > 0) {
                            NotifyService.show(type as NotificationType, info as string);
                        }
                    }
                }
            },
            onFinish: (page: any) => {
                params.onFinish?.(page);
            },
            onError: (errors: any) => {
                params.onError?.(errors);

                if (!params.offNotification) {

                    const text = Object.values(errors).join(', ')
                    NotifyService.showError(text);
                }
            }
        } as any
    }
}
