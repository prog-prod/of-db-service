import type {OfUserAvatarThumbsType} from "@/Types/OfUserAvatarThumbsType";
import type {IncomeType} from "@/Types/IncomeType";

export type OfUserType = {
    id: number;
    name: string,
    avatar: string;
    avatar_thumbs: OfUserAvatarThumbsType;
    is_estimated_sub_count: boolean;
    estimated_subscribers_count: number;
    estimated_income: IncomeType;
    username: string;
    favorites_count: number;
    photos_count: number;
    videos_count: number;
    posts_count: number;
    location: string;
    is_verified: boolean;
    subscribers_count: number;
    subscribe_price: number;
    about: string;
    deleted: boolean;
    website: string;
}
