// @ts-ignore
import { notify } from "@kyvg/vue3-notification";

enum NotifyEnum {
    SUCCESS = "success",
    ERROR = "error",
    INFO = "info",
}

class NorifierService {
    public readonly $notify: typeof notify;

    constructor() {
        this.$notify = notify;
    }

    notifySuccess(text: string) {
        this.$notify({
            text,
            type: NotifyEnum.SUCCESS,
        });
    }

    notifyError(text: string) {
        this.$notify({
            text,
            type: NotifyEnum.ERROR,
        });
    }

    notifyInfo(text: string) {
        this.$notify({
            text,
            type: NotifyEnum.INFO,
        });
    }
}

export const notifier = new NorifierService();
