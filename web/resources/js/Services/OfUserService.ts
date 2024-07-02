import type {OfUserType} from "@/Types/OfUserType";
import type {OfUserDetailedType} from "@/Types/OfUserDetailedType";
import {HelperService} from "@/Services/HelperService";
export class OfUserService {
    private ofUser: OfUserType | OfUserDetailedType;
    private readonly defaultAvatar: string;
    constructor(ofUser:OfUserType|OfUserDetailedType) {
        this.ofUser = ofUser
        this.defaultAvatar = '/images/default-avatar.png';
    }

    isTrialLink(link:string) {
        const regex = /^https?:\/\/onlyfans\.com\/action\/trial\/[a-zA-Z0-9]+$/;
        return link !== null &&  regex.test(link);
    }
    getOfUserCardAvatar() {
        return this.ofUser.avatar_thumbs.c144 ?? this.ofUser.avatar_thumbs.c50 ?? this.ofUser.avatar ?? this.defaultAvatar;
    }
    getOfUserAvatarLowResolution() {
        return this.ofUser.avatar_thumbs.c144 ?? this.ofUser.avatar_thumbs.c50 ?? this.defaultAvatar;
    }

    isFree() {
        return this.ofUser.subscribe_price === 0;
    }

    getAvatar() {
        return this.ofUser.avatar ?? this.ofUser.avatar_thumbs.c144 ?? this.defaultAvatar;
    }

    isVerified() {
        return this.ofUser.is_verified;
    }

    getShortEstimatedSubscribersCount() {
        const sub_count = HelperService.formatNumber(this.ofUser.estimated_subscribers_count);
        return this.ofUser.is_estimated_sub_count ? `~ ${sub_count}` : sub_count;
    }

    getEstimatedIncomeString() {
        if(this.ofUser.estimated_income.income === 0) return 'unknown';
        return `${HelperService.formatNumber(this.ofUser.estimated_income.from)} - ${HelperService.formatNumber(this.ofUser.estimated_income.to)} $`;
    }
}
