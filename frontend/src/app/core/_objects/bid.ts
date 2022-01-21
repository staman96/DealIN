export class Bid{
    bid_id: string;
    bid_amount: number;
    bid_time: Date;
    user_id: string;
    product_id: string;
}

export class UserBid{
    bid_amount: number;
    bid_time: Date;
    user_id: string;
    product_id: string;
    product_name: string;
    product_slug: string;
    auction_ends: string;
}