export class Product {    
    product_id: string;
    product_name: string;
    product_out_price: number;
    product_description: string;
    current_value: number;
    auction_first_bid: number;
    auction_starting_price: number;

    auction_starts: string;
    auction_ends: string;
    product_updated_at: Date;
    product_created_at: Date;

    product_status: string;
    product_image: string;
    product_osm_long: number;
    product_osm_lat: number;
    product_osm_country: string;
    product_location: string;
    product_slug: string;
    user_id: string;

    categories: string[];

    total_bids: number;
}